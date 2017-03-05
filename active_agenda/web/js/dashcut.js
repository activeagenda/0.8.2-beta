//dashcut.js
function dash_showShortcutForm(){
    sc = document.getElementById('dash_shortcutForm');
    scTitle = document.getElementById('dash_shortcutTitle');
    sc.style.display = 'block';
    scTitle.focus();
}
function dash_hideShortcutForm(){
    sc = document.getElementById('dash_shortcutForm');
    sc.style.display = 'none';
}
function dash_saveShortcut(linkHere){
    scTitle = document.getElementById('dash_shortcutTitle');
    theLink = linkHere +'&shortcut=set&sctitle='+scTitle.value;
    document.location = theLink;
}