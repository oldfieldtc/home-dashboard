import {Temporal} from "temporal-polyfill";

function showTime() {
  const date = Temporal.Now.plainDateISO().toLocaleString('en-GB', {dateStyle: 'full'}).replace(',', '');
  const time = Temporal.Now.plainTimeISO().toLocaleString('en-GB', {hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true}).replace(' ', '');

  if ( date.includes('Saturday') && time.includes('12:00:00am') ) {
    fetch("/api/clearcache/?origin=clock")
      .then(response => {
        if (!response.ok) {
          throw new Error('Cache not cleared');
        } else {
          location.reload();
        }
      });
  }

  document.getElementById('current-time').innerHTML =`${date}</br>${time}`;
}
showTime();
setInterval(showTime , 1000);
