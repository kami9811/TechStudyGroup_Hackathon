'use strict';
{
const hiraku = document.getElementById('open');
const tojiru = document.getElementById('close');
const modal = document.getElementById('modal');
const kakusu = document.getElementById('mask');

hiraku.addEventListener('click', function () {
 modal.classList.remove('hidden');
 kakusu.classList.remove('hidden');
});
tojiru.addEventListener('click', function () {
 modal.classList.add('hidden');
 kakusu.classList.add('hidden');
});
kakusu.addEventListener('click', function () {
 modal.classList.add('hidden');
 kakusu.classList.add('hidden');
});

const hiraku2 = document.getElementById('open2');
const tojiru2 = document.getElementById('close2');
const modal2 = document.getElementById('modal2');
const kakusu2 = document.getElementById('mask2');

hiraku2.addEventListener('click', function () {
 modal2.classList.remove('hidden2');
 kakusu2.classList.remove('hidden2');
});
tojiru2.addEventListener('click', function () {
 modal2.classList.add('hidden2');
 kakusu2.classList.add('hidden2');
});
kakusu2.addEventListener('click', function () {
 modal2.classList.add('hidden2');
 kakusu2.classList.add('hidden2');
});
}
