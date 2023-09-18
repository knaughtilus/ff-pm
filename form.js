function $(str) {
    return document.querySelector(str);
}
  
function $all(str) {
    return document.querySelectorAll(str);
}
  
function getFileData(myFile){
    var file = myFile.files[0];  
    var filename = file.name;
    $('input[type=file] + label .file-name').innerHTML = file.name;
    $('input[type=file] + label').classList.add('active')
}

$('[type=date]').addEventListener('focusin', () => {
    $('.date').classList.add('active');
});

$('[type=date]').addEventListener('focusout', () => {
    $('[type=date]').value === "" ? $('.date').classList.remove('active') : null;
});

$all('select').forEach((s) => {
    s.addEventListener('change', () => {
        let options = s.querySelectorAll('option:not([disabled])');
        options.forEach((o) => {
            o.selected ? s.classList.add('active'): null;
        });
    });
});