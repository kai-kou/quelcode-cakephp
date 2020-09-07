var datediff = function (now, due) {
  if (due.getTime() > now.getTime()) {
    var rest = due.getTime() - now.getTime();
    var sec = Math.floor(rest / 1000 % 60);
    var min = Math.floor(rest / 1000 / 60) % 60;
    var hours = Math.floor(rest / 1000 / 60 / 60) % 24;
    var days = Math.floor(rest / 1000 / 60 / 60 / 24);
    var count = [days, hours, min, sec];
  }
  else {
    count = [];
  }

  return count;
}

var timer = null;
var seconds = 0;
var countdown = function (elementId, nowStr, dueStr) {
  seconds++;
  var now = new Date(nowStr);
  now.setSeconds(now.getSeconds() + seconds);
  var goal = new Date(dueStr);
  var counter = datediff(now, goal);
  var timeContent = '終了';
  if (counter.length > 0) {
    timeContent = counter[0] + '日';
    timeContent += counter[1] + '時間'
    timeContent += counter[2] + '分';
    timeContent += counter[3] + '秒';
  }
  else {
    if (timer != null) {
      clearTimeout(timer);
    }
  }
  document.getElementById(elementId).textContent = timeContent;
}
