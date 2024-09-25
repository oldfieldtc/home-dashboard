<?php

namespace kiosk\models;

class Tasks extends FetchData {
    private string $authEndpoint = '/api/v1/login';
    private string $taskEndpoint = '/api/v1/projects/8/tasks';
    private string $authToken;
    public Cache $cache;
    public function __construct() {
        $this->cache = new Cache();
        $this->fetchUrl = getenv('VIKUNJA_DOMAIN');
        parent::__construct($this->fetchUrl);
        $this->authToken = $this->authenticate();
    }

    public function authenticate() : string {
        if ( $this->cache->fetchCache('vikunjaAuthToken') ) {
            return $this->cache->fetchCache('vikunjaAuthToken');
        } else {
            $data = array(
                "long_token" => true,
                "password" => getenv('VIKUNJA_PASSWORD'),
                "username" => getenv('VIKUNJA_USERNAME')
            );
            $loginData = $this->fetch('vikunjaAuthToken',array(
               CURLOPT_HEADER => 0,
               CURLOPT_POST => true,
               CURLOPT_URL => "{$this->fetchUrl}{$this->authEndpoint}",
               CURLOPT_POSTFIELDS => json_encode($data),
               CURLOPT_HTTPHEADER => array('Content-Type: application/json')
            ));

            $this->cache->cacheData($loginData['token'], 'vikunjaAuthToken');

            return $loginData['token'];
        }
    }

    public function getTasks() : array {
        return $this->fetch('tasks', array(
            CURLOPT_HTTPGET => 1,
            CURLOPT_URL => "{$this->fetchUrl}{$this->taskEndpoint}",
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_HTTPAUTH => CURLAUTH_BEARER,
            CURLOPT_XOAUTH2_BEARER => $this->authToken
        ));
    }

    public function formatData( array $data ) : array {
        $dateUtil = new DateUtil();
        $weekData = $dateUtil->getDateArray(7);
        $tasksByLabel = [];

        foreach ( $data as $task ) {
            // Only use tasks that are within the repeat window
            // If a task is checked as complete but has a repeater, Vikunja currently (04/2024) unchecks it and set the
            // due date to the next due date/repeat cycle
            // See https://community.vikunja.io/t/repeatable-tasks-appear-to-just-have-start-due-dates-reset-instead-of-creating-a-clone/468
            // for developments on them tweaking this.
            if ($this->includeTask($task)) {
                foreach ($task['labels'] as $label) {
                    // Check if the label key already exists in $tasksByLabel
                    if ( array_key_exists($label['title'], $tasksByLabel) ) {
                        // Add task to relevant label key
                        if ( $task['repeat_after'] ===  604800 || $task['repeat_after'] === 1209600) {
                            $tasksByLabel[ $label['title'] ]['weekly'][] = array(
                                'title' => $task['title'],
                                'complete' => $task['done'],
                                'frequency' => $task['repeat_after'],
                                'id' => $task['id']
                            );
                        }
                        else if ( $task['repeat_after'] === 2592000 ) {
                            $tasksByLabel[$label['title']]['monthly'][] = array(
                                'title' => $task['title'],
                                'complete' => $task['done'],
                                'frequency' => $task['repeat_after'],
                                'id' => $task['id']
                            );
                        }
                    } else {
                        // Create label key and add task to it
                        if ( $task['repeat_after'] ===  604800 || $task['repeat_after'] === 1209600) {
                            $tasksByLabel[$label['title']] = array(
                                'weekly' => array(
                                    array(
                                        'title' => $task['title'],
                                        'complete' => $task['done'],
                                        'frequency' => $task['repeat_after'],
                                        'id' => $task['id']
                                    )
                                ),
                                'monthly' => array()
                            );
                        } else if ( $task['repeat_after'] === 2592000 ) {
                            $tasksByLabel[$label['title']] = array(
                                'weekly' => array(),
                                'monthly' => array(
                                    array(
                                        'title' => $task['title'],
                                        'complete' => $task['done'],
                                        'frequency' => $task['repeat_after'],
                                        'id' => $task['id']
                                    )
                                )
                            );
                        }
                    }
                }

            }
        }
        return $tasksByLabel;
    }

    // Only include tasks if they are due in the next week/month
    private function includeTask( array $task ) : bool {
        $dateUtil = new DateUtil();
        $taskDueDate = $dateUtil->getLocalDateTime( $task['due_date'] );

        // Task repeats every weekly or fortnightly
        if ( $task['repeat_after'] ===  604800 || $task['repeat_after'] ===  1209600 ) {
            if ( $taskDueDate > $dateUtil->getLocalDateTime($dateUtil->getDate(7)) ) {
                return false;
            }
        // Check if monthly task
        } else if ($task['repeat_after'] === 2592000) {
            if ($taskDueDate > $dateUtil->getLocalDateTime( $dateUtil->getDate(30)) ) {
                return false;
            }
        }
        return true;
    }

    public function outputTasks( array $tasks ) {
        ob_start();
        ?>
        <div class="tasks">
        <?php
        foreach ( $tasks as $label => $index ) {
        ?>
            <h2><?php echo $label; ?></h2>
        <?php
            if ( count($index['weekly']) > 0 ) {
            ?>
                <h3>Weekly</h3>
                <ul>
            <?php
                foreach ( $index['weekly'] as $task ) {
                ?>
                      <li>
                          <label for="<?php echo "{$task['title']}-task"; ?>"><?php echo $task['title']; ?></label>
                          <input
                              type="checkbox"
                              class="vikunja-task"
                              id="<?php echo "{$task['title']}-task"; ?>"
                              name="<?php echo $task['title']; ?>"
                              <?php echo $task['complete'] ? 'checked' : ''; ?>
                              data-id="<?php echo $task['id']; ?>"
                              data-frequency="<?php echo $task['frequency']; ?>"
                          >
                      </li>
                <?php
                }
                ?>
                </ul>
                <?php
            }
            if ( count($index['monthly']) > 0 ) {
            ?>
                <h3>Monthly</h3>
            <?php
                foreach ( $index['monthly'] as $task ) {
                    ?>
                    <li>
                        <label for="<?php echo $task['title']; ?>"><?php echo $task['title']; ?></label>
                        <input
                            type="checkbox"
                            class="vikunja-task"
                            name="<?php echo $task['title']; ?>"
                            <?php echo $task['complete'] ? 'checked' : ''; ?>
                            data-id="<?php echo $task['id']; ?>"
                            data-frequency="<?php echo $task['frequency']; ?>"
                        >
                    </li>
                    <?php
                }
            }
        }
        ?>
        </div>
    <?php
        return ob_get_clean();
    }
}
