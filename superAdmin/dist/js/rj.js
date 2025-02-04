// for side nav
  function mobileIconBar() {
     //alert('cameHere');
     var navBody = document.getElementById('nav-body').style.display;
     //alert(navBody);

     if (navBody=='' || navBody=='none') {
      //alert('camehere1');
      document.getElementById('nav-body').style.display='block';

     }else{
      //alert('camehere2');
      document.getElementById('nav-body').style.display='none';
     }

  }
