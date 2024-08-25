import {Temporal} from "temporal-polyfill";

function showTime() {
  const date = Temporal.Now.plainDateISO().toLocaleString('en-GB', {dateStyle: 'full'}).replace(',', '');
  const time = Temporal.Now.plainTimeISO().toLocaleString('en-GB', {hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true}).replace(' ', '');

  document.getElementById('current-time').innerHTML =`${date}</br>${time}`;
}
showTime();
setInterval(showTime , 1000);
