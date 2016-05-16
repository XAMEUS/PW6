$(document).ready(function(){
    var url = window.location.href;
    var index = url.search("payslipgenerator");
    var newurl = url.substr(0, index+7)+url.substr(index+16);
    window.open(newurl,'window','toolbar=no, menubar=no, resizable=yes');

    window.document.location = url.substr(0,index);
});
