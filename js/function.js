'use strict';

var singleBtn = document.getElementById('single-btn');
var multiBtn = document.getElementById('multi-btn');

var singlePage = document.getElementById('single-page');
var multiPage = document.getElementById('multi-page');

singleBtn.addEventListener('click', () => {
    singleBtn.classList.add('current');
    multiBtn.classList.remove('current');
    multiPage.classList.add('no-display');
    singlePage.classList.remove('no-display');
});

multiBtn.addEventListener('click', () => {
    multiBtn.classList.add('current');
    singleBtn.classList.remove('current');
    singlePage.classList.add('no-display');
    multiPage.classList.remove('no-display');
});
