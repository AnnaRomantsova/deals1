function go(div) {
	location.href = div.href;
}

function changeColorBg(object,color) {
	object.style.background = color;
}

function over(div) {
   changeColorBg(div, 'panelw' == div.className ? '#f5f5f5' : '#f0f0f0');
   window.status = 'http://'+location.host + div.href;
}

function out(div) {
   changeColorBg(div, 'panelw' == div.className ? '#fff' : '#e5e5e5');
   window.status = '';
}
