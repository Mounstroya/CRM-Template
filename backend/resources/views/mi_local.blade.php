<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Favicons -->
    <link rel="shortcut icon" type="image/x-icon" href="favico/favicon.ico">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap.css">
	<link rel="stylesheet" href="toast/resources/css/jquery.toastmessage.css"  rel="stylesheet">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" integrity="sha384-OHBBOqpYHNsIqQy8hL1U+8OXf9hH6QRxi0+EODezv82DfnZoV7qoHAZDwMwEJvSw" crossorigin="anonymous">
	<title>Ventas</title>

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/app-private.css%3Fid=3a462871c0ee7353baff263b11e1f5fc.css">
		</head>

	<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
				<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="/mi-local#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/mi-local" class="nav-link"><i class="fas fa-home"></i> Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/mi-local/productos" class="nav-link"><i class="fas fa-database"></i> Inventario</a>
      </li>
            <li class="nav-item d-none d-sm-inline-block">
        <a href="productos/existencia-excel" class="nav-link"><i class="fas fa-file-excel"></i> Lista de precios</a>
      </li>
          </ul>
    <!-- SEARCH FORM -->
    <!--form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Búsqueda" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form-->
    <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="/mi-local#">
                    <i class="fa fa-inbox" style="font-size: 25px;"></i>
                    <span class="badge badge-danger navbar-badge" id="totalNotify">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="/mi-local/productos" class="dropdown-item dropdown-footer">VER PEDIDOS</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="/mi-local#">
                      <i class="far fa-bell animated infinite swing" style="font-size: 25px;"></i>
                    <span class="badge badge-warning navbar-badge" id="hTotalSugerencias">65</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header" style="font-weight: bold;color: red;">65 PRODUCTOS POR SURTIR</span>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia28">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT206 AUDIFONO BLUETOOTH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(28)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia37">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT250 DIADEMA BT 1HR
              <span class="float-right text-sm text-danger" onclick="no_sugerir(37)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia190">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> BG-139  DIADEMA DE GATO LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(190)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia199">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> BS-09 BARRA DE SONIDO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(199)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia226">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MB-152 BOCINA MINI LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(226)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia235">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P47 DIADEMA COLORES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(235)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia244">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P47M DIADEMA GATO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(244)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia262">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> PAST-001 PROYECTOR ASTRONAUTA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(262)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia271">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA314T BOCINA LINK BITS 3&quot;  TWS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(271)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia298">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA369T BOCINA 3&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(298)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia316">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> OSO GRADUACION
              <span class="float-right text-sm text-danger" onclick="no_sugerir(316)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia325">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUDIFONOS SONYN202
              <span class="float-right text-sm text-danger" onclick="no_sugerir(325)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia334">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-3206 AUDIFONO SAMSUNG AKG S10
              <span class="float-right text-sm text-danger" onclick="no_sugerir(334)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia379">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUDIFONOS ZTE FRESHSUN
              <span class="float-right text-sm text-danger" onclick="no_sugerir(379)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia388">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SMARTWATCH ZTE FRESHFUN
              <span class="float-right text-sm text-danger" onclick="no_sugerir(388)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia397">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> X10 SMARTWATCH EARPHONES X10
              <span class="float-right text-sm text-danger" onclick="no_sugerir(397)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia496">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CUBO IPHONE 20W SIN CAJA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(496)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia514">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CUBO SAMSUNG  45W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(514)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia550">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR IPHONE 16 C A C 35W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(550)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia568">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR SAMSUNG 45W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(568)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia580">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR SAMSUNG V8
              <span class="float-right text-sm text-danger" onclick="no_sugerir(580)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia590">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MJ-6699 AUDIFONO INALAMBRICO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(590)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia600">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TB-6310 SMARTWATCH T500
              <span class="float-right text-sm text-danger" onclick="no_sugerir(600)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia640">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARRITO 8 WHEEL STUNT
              <span class="float-right text-sm text-danger" onclick="no_sugerir(640)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia670">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR XIAOMI TIPO C 33W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(670)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia740">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SOMBRILLAS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(740)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia770">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA370T BOCINA 3
              <span class="float-right text-sm text-danger" onclick="no_sugerir(770)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia871">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> KTS-2048 BOCINA 8&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(871)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia881">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGNEBROPROMO PROMOCION CARGADOR NEBRO TIPO C
              <span class="float-right text-sm text-danger" onclick="no_sugerir(881)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia891">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> LABUBU MUÑECO TIPO ORIGINAL
              <span class="float-right text-sm text-danger" onclick="no_sugerir(891)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia911">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MB-168 BOCINA 3&quot; LINK BITS COLORES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(911)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia945">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XB-5516 POWER BANK 2000 MAH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(945)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia957">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-3232 AUD EARPODS LIGHTNING CONNECTOR
              <span class="float-right text-sm text-danger" onclick="no_sugerir(957)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia969">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FM-8226 BARRA DE SONIDO A500
              <span class="float-right text-sm text-danger" onclick="no_sugerir(969)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia981">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XM-9010 CUBETA PARA BEBIDAS CON BOCINA Y LUCES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(981)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1008">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA1238TKL BOCINA 12&quot;C/MICROFONO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1008)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1020">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA8061T BOCINA 8&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1020)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1032">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA438TBOCINA RADIO 4&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1032)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1056">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIEADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1056)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1068">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIEADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1068)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1092">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-2007 DIADEMA BOSE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1092)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1104">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1104)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1116">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT114 AUDIFONO BLUETOOTH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1116)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1152">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> ESTRELLAS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1152)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1327">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FM5125 BOCINA SPLASHPROOF
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1327)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1571">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TWS G-TIDE H11
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1571)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1643">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIADEMA SONY WH-1000XM5
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1643)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1667">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> GAR264 BATERIA PORTATIL 10000 MAH 3A
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1667)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1694">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TWS G-TIDE L22
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1694)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1886">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CAB177 CABLE V8 2.1A 1 METRO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1886)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1934">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA KUROMI 7&quot; ANDROID 15 256/8 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1934)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1958">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA STICH 7&quot; ANDROID 15 256/8 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1958)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1982">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA BOB ESPONJA 10&quot; A08   AZUPIK DOBLE SIM ANDROID 15 512/12 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1982)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2006">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA UMIIO S25 ULTRA 10.1&quot; 128/12GB ANDROID 13
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2006)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2099">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> KTS-1841 BOCINA 6.5&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2099)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2165">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> GAR159 BATERIA PORTATIL 20000 2.1
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2165)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2401">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P9PROMO DIADEMA P9 PROMOCION
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2401)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2426">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA370PROMO PROMOCION BOCINA VA370T LINK BITS 3&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2426)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2451">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XR3101 EXTRA BASS EARPHONE XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2451)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2476">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XR3109 STEREO HEADSET  XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2476)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2501">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TR6061 OWS T2 AUD DE BOLA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2501)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2526">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> JXQ1403 EXTENSION 5 METROS XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2526)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2551">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FEE-40313 DIADEMA GUERRERAS K-POP
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2551)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2676">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> PLAYERA SELECCION MEXICANA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2676)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2997">
            <a href="/mi-local#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TERMO MUNDIAL LARGO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2997)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <a href="/mi-local/productos" class="dropdown-item dropdown-footer">IR A MI ALMACÉN</a>
        </div>
      </li>
            <li class="nav-item">
        <a class="nav-link" href="/mi-local#" data-toggle="modal" data-target="#updatePasswordModal">
          <i class="fas fa-key"></i> Cambiar Contraseña
        </a>
      </li>
      <!--li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
          class="fas fa-th-large"></i>
        </a>
      </li-->
    </ul>
  </nav>

  <!-- Modal para actualizar contraseña -->
  <div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatePasswordModalLabel">Actualizar Contraseña</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="updatePasswordForm">
          <div class="modal-body">
            <div class="form-group">
              <label for="current_password">Contraseña Actual</label>
              <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
              <label for="new_password">Nueva Contraseña</label>
              <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
              <label for="new_password_confirmation">Confirmar Nueva Contraseña</label>
              <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('updatePasswordForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch('/usuarios/update-password', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': 'TnncInpaEGGbJzbpT4sqwEzlwJ0CeKyE3iyGXLeA'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Contraseña actualizada correctamente');
          $('#updatePasswordModal').modal('hide');
        } else {
          alert('Error al actualizar la contraseña: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la contraseña. Verifique la consola para más detalles.');
      });
    });
  </script>
			<!-- /.navbar -->
			<!-- Main Sidebar Container -->
			<aside class="main-sidebar elevation-4 sidebar-light-warning">
				<!-- Brand Logo -->
				<a href="/mi-local#" class="brand-link" style="text-align: center ;">
					<!--img-circle  elevation-3-->
										<img alt="panel-logo" class="brand-image" style="opacity: 1; float:none !important;" src="logo-fd3.jpeg">
					<!--img  alt="tienda-logo" class="brand-image" style="opacity: .8" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAABmJLR0QA/wD/AP+gvaeTAAAFRUlEQVRYhe2XW2wUVRzGv3PmzOyt0227dOlFe4EqVECzsfFBQXkx0RDe5IEoRo1BEngQL/jgy4YnovWS6JskoETj5UVRiYkkVuqDEKFgwSLR3rulpdvttrs7szNnzvGh7XZ3u4stI8YHv2Symf98c87vfDNzzllgBXrnxRP1bx04dnYlXjeiKzE5nAcB3H2bWVYGk6/O/UebbwcIcAswBErv2/s+bPxPwIBAJV6h3gYWkPyT6DPHvLpO9gKyocgWgpR7QPAuJHlZQh4lBEk3HUuJsVQKH0SPP2su1li+oboaRyor+Qst67i3TBuvLfzucwMCAAP9zGRMWQfgYEkYAlFTXSVYOMzd9vW3SkwTlkqSUH6tAMbM0kP9/cru2TFVqHlv07RJiI9J+Bjkajs1OIjBCWq8MnevLYCpLAF35KGyMAqVj/gIwc6gRpW8+iemha0BhjqNFrxjK9G4JdE1y7FD13L3Cgl8FrdFWpCHAXy+WM+NPxqNUkrI4Y4KRVGkBBaOtCNgSKCWkVyt4BASvWlnvocS18MMyAiJjLNUo5CIBChjVB6JRqM5hlwy+lTTLkZIbbPIEDuTNzJOUUMVCCMNUWLkXAI/pzW0SQOsTG7VRMV4ykATW2qhFcA5aKHKGy1PLKZDF1MByOsRzXaUooYmHYJaWgpj5apVBCadQlIK4H7N8RLIw4vpUGAxFdRuUMWa4oamBUVYWfV7W6CwIjEtlsfWpjqaShHWp5p25WAIyMF7VR4vNR0/5LHRzNwl08IEHvQsny4ogIjmMALyUg4GwOYWJtuLzY4EJgTFjFP+IzJk4W8pzTgEE4LCKeGpV4ROQO7JwVCCs5ctJZVvSgiCLw1mX4I2d8pkosdSlk0yQ5yKrwyVV3uV2ZOGJoZ4YbYSQI+l8FOmxn+VWvqkoTqJosd1wWJXBOQZYOFrsqXy/B9c/pgwmL+eSjZLaHKU04qO7U2yY3uznowb+PbjXjFi2OYm2BYTIn2NepxJiYbHn2xXWjaEKgd+j+P7L/rkACXJ9dz0GlIZ+02yaqqr/t1PbfEGQz72S9ew/KZrSDQxkdal0Ecdkk0I4qcM+4G8hfLNVz4K+G1xMVjlCbdG6vSN960letXSEsVtgd5zMfT3TYHbDhpbqhDZdicCFVrOk05ZuNA9gtjgDJiqYF37Gmx5oAEsbzqfmzHR1zOBwYsTc4kZ47qpKZFXO59OF8AAwPuHTnRve2z91vZIXa4WG0oiM2ctf9irlF/X0NAczJ339VxH93d//nTgjT3bFmus5J0LGh2YwdcnLsMb9Jf1ZNNZ2IYF1afBE/CU9ZnJDHbu2Yw7WqvKem4KY6Qs+EM6GrZHynp4JgsjnoQvFATzl4eJdfXASN084ZvCrETM74HuD7ttBsCtbDuLNN03hKuf/oB437BrGNfJ1GxsQnB9IxS1eFVbvVwnw00L2cQseNZ2DVO07QS90D2Cqz0T2NQRhnQkMtNzGDx9vjxM2gQ3LDCfBhYot3UG+Gwagtfi2qVxXDk/iUzKAikKo3Cnp8AXrlNQ36iiOpDBRMyEoAKGEi8/nMr5w4YNG+myNoVqsFMJhBs8uGuDivExByOD8JWFAYDatRpa25bmFcIAGXC3agMAWeAMVDC0tjFwLjAyaBR4XL8z/6QYAHTuP/YoCGm1bRm6PpqF48xfjE9aEDaAWfdfirCB2FgWWXt+7b8xnoVty1DngeN7FSL6D7733GkGAJRghwA2cVv0x8ayydhYNteIV1N9sFT3CWoQo8OmMTqc+wMJbos4gF0C5DKA0677+F//hv4CTIE3Sy62000AAAAASUVORK5CYII="/-->
					<!--span class="brand-text font-weight-light" style="color:#0099ff;font-family: 'Abel', sans-serif;font-weight: bold !important;">Bodega Principal</span-->
									</a>
				<!-- Sidebar -->
				<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="row">
    <span class="brand-text font-weight-light" style="margin-top: 8px; width: 100%; text-align:center; color:#0099ff;font-family: 'Abel', sans-serif;font-weight: bold !important; font-size: 20px">Bodega Principal</span>
  </div>
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
      <a href="/mi-local#" class="d-block">Administrador</a>
      <form action="/logout" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">        <button class="btn btn-danger btn-sm"><i class="fa fa-power-off	"></i> Cerrar sesión</button>
      </form>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

      <!--MENU LATERAL IZQUIERDO-->
                                  <li class="nav-item">
            <a href="/mi-local" id="menu_local" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAACg0lEQVR4nO2W3U9ScRjHTy8XXdV9F63Vv9HWRdS6aCuZ6FzzAuMmt7xJh7zU6ZS9YhfK0sHIYpgQNgUmhyASoRfAYVMCs0gy001yCCVYlPNpz7ExBYTW4OAFZ/tsZ3uefb+fnXF+B4LYcJEkuVMh5Bzoaj12qNRgD/YRuS6FkLOP7q7z+AcvxAOGpkSpwR66q87TQZ7cmyWju13VuPjqMiTfXGcN7NPe4Z7PlpFxRTEvxapMzEsB9mbJ9N48JQ+YL0LwaQtrYB/2Zsn4rS1BWNIA20xYmieBIHakRbQy7rmfc8q1csik5lVr+vZqASOi5/F2vXdIw+UQgb9MPRd/Ismju4knHTzJ6sL9sonAkgZWF3pgoLNGSgwp6iNBWyuUmyFFfYQYUfNn8RUrNyNq/izxUiOYYfNsSW4BemwpE3VfYWBrxsi41A3+XEP34CUGtmboQZjktfpl37VNg+WxNnCa7oLT2M7cl3zmawOjvFZPqEjOfrOy4WPMs/5dinspcPZLIDC/CIG5r+DUiyE+erVkM+yllYIQeqz/j1GZzlhpDbyw3APXsA6moisQ+g4MeO8a7gMXXfwZ9tksvUApjafTnwPJQ88Jz0wivRxiEezF/oIy45EU3Oh/BzJDKC+Nna8L7mAO5v23zOiXFZBqwyCzxPNSQ9kL7mAO5lVkZJUnY6n8ZuKVtwkq58y2PYGfhRNg//xjEwOBGIj7iiODOZiX2YG9WTLW6SSYM9CNR0FUJBnMwbzMDuzdvjKiHvdxw9toiv7w7ddGHo9FfjcpJ0D0aDov1aSt4A7mYF5mB/Zif1qGfODYI+x2H8ykWeE7zL/lOFKIs5S96l/2MC9XD/ajyB+DTlm2C91ZuAAAAABJRU5ErkJggg==">
              </i>
              <p>
                Bodega Principal              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/punto-de-venta" id="menu_punto" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAACmElEQVR4nGNgwANCQ1cxJ1V3y2dW9ClRglOqJymmpc1kZSAXVHbMzeyYte761OV7PkxdvucLZXj3p+7ZG+9U9SyoJ9khWSVdEv0Ltz7ecPDKf2ri6av2vi+sn25GkmMKWmeFLd12kqoOAeF1+y/9Tylue5pQ1DIvu7RTiijHlLTMSVi+4zTckN5tK/737VhGFu7cvAjFQVGZ1f9LGyb+L2mbdZosx/TvWfJ/14O9ZOEJ+xZiOqZx4v/63oXPcusn8g0Ox0xY+Cy+vl9g1DEbqBUyvdROwJQ4ZgMVMcWOufHw1f97T9+C2fvP3vn/6MX7/8cuPSAod2XFxv83F6+mrmM+fPn+/9fvP2D2ySsP/4PA5TvPCcq9nDnn/6dJk6nrmB3Hb/zfeeImmL358LX/u07e/L/lyDWCctv3nP2/Y/eZweGYHUfP/9957NzgiKYPz9b8//V66eBIwDdu7P5/7/b20az9Hz1ktl488X/r5WODI83suH3w/857+wZHbtp45CIYDwrH7ECSG/Bo+oAkR7XG1Q1yszaSHNnNzgKaNsg7nicUtC5Mq+qTJMoxyZUTxGnRVZmxat87krsqIFDWtSC9fdb6a9OX7vg1a/nu/8Tg6Ut3/p+0eDsYg9gw8RlLdvzpmrPhdmX3/DoGckF9fT3TsZT0PU96+v8TgzeU1f63dQ8DYxAbJn48PetsfX09CwOl4EZ8/Obv/f3/icG7y6r+mzn4gjGIDRO/lZx8lGKHgMCoY3CB64Mpmq5ERS381tdHkWOux8ZuoYpjTnh4yFyLjT31tKjo7dOSki/48Nrk9O8wx4DYT4uLP9xKSLhwKSDAmCqOgYFjTk7Sp5ydlfDhZe7u2vWOXnYgDGIfdnGR+8/AwEisJQAMHsun8I0LRAAAAABJRU5ErkJggg==">
              </i>
              <p>
                Punto de venta
              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/caja-registradora" id="menu_caja" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEVUlEQVR4nM2Y7U9aVxjA3Zcl26d9WNp+2ky/7j/Ykv0B+7QsWbIsTZplW22XJcZhRTcpHdZ35VqQN3lTuYJFAQUUcEIqBSqt4AuKiK2oYFFQr7a6dS/ts53bcfD2ZWP4wk7yC8nD89znd++553BCUdFLRnmT+gy7Xl2cC9+1yN7O1H2q577+hbLy7H/hM3nV6aJ/GmqDwz0TuU/lgkJvM6Ka0t6aD+tHRBHZOPlQdluzlysib1eabWzSvVLG6PCMQo5Da3bpUc0PlpaekWUn5IPE17lxTsw6dWQyV6x8fb4ysnGSOq9mF/8/ZSobVR/x2vXWOqnBrrXc3MxVRtk/kkQ1V/slySOSgdcaO4xzgcUkTMU2wO4J5uoC1rEAXSNzG/61Kau/HgYiQ8Cx8EHh18L1W6oXZc5zibdUJnc6HN8ChMM7lbOMzR2ka+S3jHk9FUSHv4f6XMF6l5a5wG57R2fz7x5GRuOzwWB0OC+ZWrv4PtqjaJmvKvjvGUYDvx5GZnY1RQupfWYMMUyC/Cc7KJ0ODGG5wchpHdI8uSRqOodf3hKO4AOLO/T0MDLhl9Brvw0DY7MMui0+Ro7ZPfP0a+7197HMtzzpJ/bxCByHjOlmiCGjNLkZOahvKU/2MZZh1SkuOgP3YGIhAS1KEwi6zbCc2s2JRqke2rosML208YIMup7NGwabdw4zcjfMyHFO3IPyWlkJlqloUHPGpmOgsbrhQhUBNe3aV97t87AbFHSNeWwy55qDjE3F4HK9qhrLXG5UEb7ZVfDNLgOH0EApTwxlNdKcKOVJoEakg+Dig7xkvKFVqGhQ87O7b3OXdDycyOti4UMyHo5DVUu3BMtUt5Kd/shaQWTuRNaAw9eosQynraf3bjT7mNe29yC9+zOsU/s4tpx6SMcQC2vbdGwxSeHY0voOzs3E4puPGI0TLg8kHS5Y9dzBMdT3CkFmzzNcoXYguLiOE/Yf/04v29/+eMJokBlIIiOYGegGMrmZsbP/mCHzqEMOvxAEUKQOx9Dv4VWh1oRleEK9berA0tw/QRnUl9d+YxjLXBP3uaZjKZwQfUDRDdFnJoamBsUQ83/H5hPZWCTxbOoy04fITCe+RjQO0YUVWFjMLpaZWAquifqcWKZW0u+bXd08dpnA0hZMLG3BZCwbD62koU5i8GKZhg5TcO5A0f4xTVO7cweabTugcmdfdtS3QW4KYBmi0zofLpBMOL4FfJV1HssItbaVg1+uHdPSHprchsHANjhCzOkTkraVZwcrruzNjj7X+klvduEDyPtdybKy1jeKvilvOqOxeqlCymjMHurLqrbTRSVsolimd1LD3ggUCpneSSEPWkbcO0o9fyIbOEFQ/yORIYf8UCsxADnsL7zMj8Je+nCFPgsuox7wQDWfBPWg9/AyF1nNp4Q99o1CvjMC0r6BPOi95vuWLp1AY0+LdKN7J41AY0+j/ox/HpDZpUr+2ZMGP5G/JP4EIXjIKRcVStcAAAAASUVORK5CYII=">
              </i>
              <p>
                Caja registradora
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/historico" id="menu_historico" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAADe0lEQVR4nGNgGAUMDNHth5NSJ5xaHd1yIGlAw8Oreot8yfybL7u3f/hfNP/mS5/yTXI0sSgt7QwrITWhNdvVSxfe/gxyTOmiO+8D6reqUdURoe37jNMnn7mYM/3i7ZiuI5n41PqVbpCKbDmwPX3y2X2xXYdrGagNEnqOLQb5FIQzppw7hschvL6VWxvq6+uZSLLAr3QDb1T7gfKI5r1OhNTGtB0sqV7+8Ffzhpf/ojsOnfOp2GSJriY0dBWzX+WWOtfindwMpILEvuN72je/+V+66Pa7qM5DPrjUgXzpV7mlJrR5f2RE6/5cULrxrd7q5Fu1tRaUOMM79mfGdx+d5lexpdWrZIsEAzkge9qF26Bg79r6/n9Mx6EJuNT5VW7ND6zaLokubl+/nyW0cffs2hWPf3Zte/8/acKp82Q5xK9ym1Zk875D2TMu3k/uP3nOv2prrV/lthAGhv+MKOqqt0X712zVxWVObOehto4t78BpKXPqhaskZ0vfyq2uvlWbC0AWI2dV34odKn5VW0rDmvZXZkw6fzqx9+RF/6rtCfjM9KjfxpfUd3x7+pRz5+I7j2QQdAQozhO6j63PnXXlQUzH4XMBVVv88alPmXjmMiTnvP+f0H10PgM1QUDtFuPqZQ9/giyoWnb/d2DVdiN86pN6T2zq3Pruf/P6F3/iOg7Vk2JX2sx6rsylTXE5y5sSYDhyZpEIXIF3xRbBzKnnb4HiNnPK+Vv+9esF8BkIko/pPNQf3XqwApRdSXFM/PxyhcmH5n1cf2PLfxCef2bF/7QF1W4oimLq96tEtR6sCe/aq8xAQwByzKyTS9/verD3PwivvLwe0zH0AvEjzjFFa9oWd+6dfh2GS1a37Rgwx9Rt6VsFswCEm3dM3jOoHJOyqFoROQtnLquPIdsx0Ytz+fJXt/QUrW7vh+GEedWyxDomf1VL5corG8BZGITbdk55QrZj4knQiMsxW+7sgIv17J/5YHg7pnhN+87WXVOOwnDhquYaqjomdFU9W/z8fAFUXI5VY8ee6eeQLS5f1zmDqo7JWd5Q2n9wzovJh+c9B+HSdZ0PB8wxpGjsGHXM/NGQYRhNM7uGRG7KXd7i37Bl4taGbRM3g3DJmrZtMdOKxSo2dO2AidVs6t2VvKBSr3ht21KYGAgXrmjJKl7d2oAsVrK2fTIpZhKsDugNAHCgPoDg7qQZAAAAAElFTkSuQmCC">
              </i>
              <p>
                Mis ventas
              </p>
            </a>
          </li>
                    <!--Menu llamado depositos-->
          <li class="nav-item">
            <a href="/depositos" id="menu_depositos" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAADhklEQVR4nO2Wy08TURSHu3SjMcaVbox7V7pzafQfcEVcVDE+opFGECj4rAJqLAoFITN90ynM9MGU0hcVSmkrnapUKEgKFBBNVFBDlIcIpcfcWwQfWChYyoJfcnMzd2bOfHPOuedcHm9LW0pSGUT2biF7v/6G5aEuXUPI3q9HHDy+Mn8fGdCOO1+3QLoGGdCOIw4MQ3DURDphCI6aWIQhA8nDaDoMoA4YwRi0A/uyCc/omgoa1+CZNcJYBhxA+upgcOwNLCe0TvjqwBJxpBbGNuQEqY+G79FZSCR0n/TR+PmUwUjb62ByZhpWo8mZKfz8umAa+m0g52hQcXrQ95iXvDLYBOauFkhG5q5msEaWvKPrNmO7cj8NDf32xDCNAw6gAiaIzkexsdYwB0zIhF9Qd+hh7OvnpGBGv3yCmqAev0+HTODu4/A6sq/hTDj//gkjD9DwbXbmN4M1gfjuQC6PxWJJwaDnZf54qJCdXzU9+w3kASYBzDPmnzCyNcJI1wqD3IbcF10Ikyvcvq4wvf/ycTFMTIgFV9iP1+dwmNjEYYonsB0nmJLTLZPAzetKYGQP2VX4GTCvlMArbm0/2tpTqwKZmJnCoU1p0SNXU/Tm4kXPPvQkxe0g4gDCWweRsZFlQSKjI0D4alPfDpy/DE0w3igNC43S8LNRvtzARulMwdicMBlE9u68+ns+sYuAcq9iw4fYRQD6Pj52Ip1VXT1GdRpwLdlorzDdLKDvLx7Kz6kK9wqY208qvAqo8qlA4lXAI7cMJB45lLXJ8VzhVUK5Z+k+WkNz5VMVVD1VA8lRoA7qgOkxganPCo5Vbuu/YJAEzO180k9NqjroKarTCHSIxYYb+mzgGG5e0SiqKQiiNmQE5XMaqtvVIPEo8A9gcPRDHgXInmnB0NsITQs2l4XJlOVuPyUX7j+pyD/AlwoP8qVXDl/Uio5n0aILOUzxrTzjPfFVs5gQ2croYkeFtdj5mLvrrOorb1OMqjt0c/VhCziHV/DEcAuwYSsoX9A4X0rdUrjeWPohU1lwhPc/dFp6eVemuvBQDlN0Pttwt/paQ6nljr0yWOaWvdN2GucTVWFUGAW6ogJeqnVCc2nH6ZqCozn6Eskta1nrQ7f0rf6VOZYWmD/FVwp2CmrvnCk0PWDFLmIIHW/TBvOn17J1JTdFNklvlk4k5G0G8ZU3t2XKcvekm4O36fUDqvfcNMQDMu0AAAAASUVORK5CYII=" alt="money">
              </i>
              <p>
                Depósitos
              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/depositos-consulta" id="menu_depositos_consulta" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAADHUlEQVR4nM3YSU8TYRgHcEw8ePDmza/g0Q/AQaCJLDEscRRlCcYQTdwTY6KpIYEWU6BAWAsooEZBCARUiGIBaaGdoVPWspZSaGkhwJS2dO/ftAnhgEBDZwpP8r9N3vllJs/zvjNRUWe94gQjV3kCecxJE1s48pTPx3lWMGmlJP3m28ymoHNu4yThCYeRUqJcYQVElFMTJsaJk1Z6hQp5HQtIFitXwwYRYWLuVKqgZfzsgAgWMEuMnx0QwRKGFRDBIiZsEMEyJiwQESYmVUyiSW46kEbZGojyUaSKKU3EMPX9y6g7JGW9WiSXkIaIYY6qwLqB9c8ERrtuR1KRwnwmMJMrO7j+btgWUYzd6cWm1Q2//5QwVocHDQN6ZFXTyJGM4WHjJG5XjOJt2yxm12zBa2aNtkB773KKmTfZkF2jRt2gAZp1z/582fbj5+Q27teP44vcAIfbhw8DeidnmHWLC1k1NAYXrQeG3V7mNr14/lmDbpU5AOfuNeV3zqOV2jgUspepdTcyq2modRY7J5gdhwfZterguF8KIRV9etRKdS5OMOTiNgq6FkOCBPJLY8GrFo2XE4x0agPiXn3ImBGdHY+bJnycYGgdE9yNQ8X0TDN4zdWT2XV5kVFFY2HLFxKmuEeHxkEOW7u0R4uGv8ZjIapVJ26Wj2JCz1E37XXUPYkaXeNbh0LGjC5kS8aRUTuBvPY5TxRwjhNMoMwWJx68H4egWwv5kn1/tpjdwYNVaimFR1+XUUV5kds8h0QR2R7Nl57nbG/y+Pz4rjbj2aep4LfT3UoVciRq1PTpML1qRXoljfzfDKooH3I/LtoSRMo/0XzpBU4wx5VuYxe3KlQQ9u+gmvLhSYvenlikJKP50osRxwRqxmhFWpnKLxq0B0Ev2gzOBJFy+lqB4lLEMYHqUpkcN8TUdonMEQS97DC6E4tJWcQxbq8PbaTRwRMqk5PFNFM27EI16UOCSMlw8hdC8J+0KlYtP+g1a/OQ3k6Uk8HDVXyxIjOpmGJSymgmqYSScPJ/hndE4oTDsTFCWfzevWIKhy7zhIorR3bUadQ/uP5wgJe9gbcAAAAASUVORK5CYII=" alt="view-file">
              </i>
              <p>
                Consulta depósitos
              </p>
            </a>
          </li>
                                        <li class="nav-item">
            <a href="/whatsapp" id="menu_whatsapp" class="nav-link">
              <i style="padding-right:15px;">
                <span style="font-size:20px;">&#128241;</span>
              </i>
              <p>
                WhatsApp
              </p>
            </a>
          </li>
<li class="nav-item">
            <a href="/creditos" id="menu_creditos" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC0ElEQVR4nO3XXU/aUBgHcD7KtuyzLLvYB1iyLUuWeLG5qyW78mIXXvhCJmKmOFycQeV1MA1ok41NrUqMrFaYw1VQSktLC4htp4BgfJYegyGbmbQ4jIlP8g/5c3H45XB6CAbD9VyVeWV6f+Ode9Y8+gEzaYnF7u/qHLDdVNd4PeK+bZsKjjlnF7xa4ggs+LqGJm+dYjoHx+/wYh60TvXoCPBwlLXY/d5PoUhlR9yDVE7RlDUqBT1Wz72mMbXh8pJmxJmYh12L1hdDi1vNYKSDclOYl5blxOPe0Kih3RLd6fHF4TIxRl8c2ocjScPz4Qj9J4b4vgXBpTVgOBH1FXIT9foshTcuFKM6zsRonX9hmHQOBCICTKagD1M+rMBBqQzV6hHqpfIh6rVUKtWGMXwsASWTCdJJTh9mg0rCMvEDeHEX9fXYNuq1JNlMQxhxlQTZ7oTim0FQbBNQCGDApLOt/5ryXxbQjuxbrSC7PKCM2aA4MAAcldSGIS7gAO+PjECpvx9Yfvf0PZYRgOHyrd8Z2elCO1PwzwKbylzu08SmMqBM2BGoZDaD5J0CLk5rxxAXeM8IkRjIjpNdKprNIJAbl3fPpGooIopAyvhk6zGFAAZZPITuGS7BQG4ORxjZ5W49RnJ5Ts5LXQ7eWoGjdprDrOg5M1kZ+M0E5IPzCCKurgEj/P2T0NIzw1FJdPGl6bMf73MxDJ8FapuFgvQLdTotoF6fbYZvCHNezsWI+T0EUvaLqAu5Aur1qf1u/XeM1rnGtGRnun1xcGIhCOBkw5nB14H8ScPx8THC0FkZcJKC6flvMD0XbjiTMzj0fqzDGDEJ9KR3mgH3ZwJhvMFV1PWuhTBPh8iE3gWMmASmqRh4vkZh0L+pew01qsPwqAd/1tYfnmgzh8e15knfir/DkVKM2B50OGhF7XrWUT9fdTT9P/1+59zdB92Lfepr04tdz1WY31QLdJeS4e2VAAAAAElFTkSuQmCC">
              </i>
              <p>
                Créditos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/cartera-de-clientes" id="menu_clientes" class="nav-link">
              <i style="padding-right:15px;">
                <img src="data:image/png;base64,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">
              </i>
              <p>
                Clientes
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/garantia" id="menu_garantia" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAExElEQVR4nO2Yf0yTRxjHux/ZlmzZsh9/LNmy/bFk/23Jkv21AYoiChgGKmMB2vdKsTgEnaAC7R0HQ2m5K5sMUGbIomIcY8aZqQSRYBxxbgYJxpEpG5iADDYYiYMwUdtb7tp729oiL7/JwiXf9M31fnzuuXuee95Xp1suy0VbMWDyNoDkgILofgDL3tItpaLHjjcBImMAUcalQDLK63RLpSiQNHIwYwFxcXkgG3VLoSjQESctV3s8gx3+NkM8u0U+WFS4rKzyJxVEuzjMx3tKnKN94WysL5xl7N3r9FixW8H4qUUDBIhCaa2WlhTGht4Xam5OUa2oQGJdFLg0bH9VOgbaB13OP0NUQP4My6E4iwCR8VQrfX3BARVI66SVrnfEqnBSXVdjudPIs/j1gsIZrY4QgNzeWn10ewCc1P4jn3i32kpXLghcQkL9YwoiHXzSzYV213BPxKSAIzcjmLnI5tlq+gvG+PF5BwSIZkqrnG4wTgon9X1Dqo8VydZ5hTPhshcAosN8sp2OIufdwdApAe8NhrLdDiytOGLGjpfmDVBBtFpao+PyBnbnj1DW/2u4ABm/Fcq62lcHiLe58tMmb/CG5MC8wOmh4x2AyH0+CT24S0C1NEaxGIOJdV9dxe4NhrAsSzJbk5Tmp/Nno0XbsppdnrhInamW0nfnGI89AhC5wCcw4VLXQNdaMem+qk0CIn2nnt0dCGF9neEBgLwNbzv4WyQzYbv7nkbkIh9zzvCMkKTILao/aVbPV5aPxVqb14m6BwG3WZLV9nXfpXsdpsCRPCdwGbjyGYDILT5opm2Pc7x/pTphghmoIBfOBQdM3OL19H/7V7BttmJxTwNIB5Jx+bOzBlQQsctVX2z9yM9DowymgLPGt9sXMNpg8uvT+kOSj8NQ26zgDFbyhoLIHT5YcVW+y/WX977lik8zqiDcmm2tkezS+bV+gBs3+8dKPsanlRYJODGrxBZAclokooiw7mvrA2IctiX6wUQmm1jNV/EsM997NgvtiQH9bnbGiDHdDkNPzdR6kXIrDn2zNWgQvt4WwdbpvdssFWtMFb9RehO7cWV10L41xzK9DoNozLTgEjB+AkByw52I2pz/9LqDcTA1nYn2O4tSvO5cQ9Sk/W73rmJbikvcDoPo7zz51QyoIJInV9fUbJjyOuvrDGeV1RvZemASqqreIOqm6tfYpPg6TK42OExeBpDc5p3yP0Ou+xruW+ZRWo5eSGt7nthaPkfyJWvUYCl7ZUpAAMlRuaprbfGaJ2MzAOTqbI/zPYu1D4ezOt6TiWjV4R3TmojNEJDri0PZMvN2Gaw0LCgcxvhRBZLLMhEd6lmzYIDDPRFiTg9kO0+Kg1iPpEtTnzzjH/21aGIghIHtBiH+PN3+J06l+Wy1w+wHl5Rne16BZIj/uaO02DkxEKZp0LHeMHakNo7lFCSJtEuGGP7M6/h/vI22BYax7NIi6TB/6/NKXvR6LiQVkr7tUoLmVY/0rBA3x8PE22gd7+cfP/R9n65QrcfvRF5JDu6e9tawOZb9y1z1njbn2p/T6bH9NUmdTYpchRVWtpjKJu5t5lLjIoCk2fvBZ8no7ANJKYXiawGk9YspBdI6/j0nJYc+PeWtslz+D+U/T5o88Y0xYQ0AAAAASUVORK5CYII=" alt="glass-hazard">
              </i>
              <p>
                Garantía
              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/garantia-atender" id="menu_garantia_atender" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,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" alt="toolbox-emoji">
              </i>
              <p>
                Atender Garantías
              </p>
            </a>
          </li>
                                  <li class="nav-item">
          <a href="/auditoria" id="menu_auditoria" class="nav-link">
            <i style="padding-right:15px;">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEpklEQVR4nM2X/U8bdRzHO58ypz9scS4xOh//AsGpmU9b4sOPxodh4kzUuOGCTxu43pUOvo6Bpfc9Sjser/e9a2mhQAu0BVoC8qBlbHQz0wXGXBQm2YJDiDokbMSZM58u6JXdtVdgw0/ySfv93ufz/r7u8/l+71qdbgUMIXSb7v9gqKwhzWT3DR2weR5fXRDkvcNSEzgVio5IVlfrd5kcd/uqweRaxLTazoH5cHRE8nQemz/AkhtfnRxcuYnG/LsUY/8InMYki2Lsr+Zg16YSR8u46O/52+L0/2woEu6FeRrzH1OY30czwl6KIe/B/JIXN/HezBJnoL+oqqGYwmQ/zZKdqNSxXh5jxOQhq9M/ODw2cXV8akYaGp24Wlrjj37OcI/J4+gva+6hMf8+xfC0yd5ojumSxt2aq0Baei5A6V3h/nlkczytFIcQt87fF70IIAsOY5hXijfaxK3u0MAV0OWbey5oqhYE8f7uSUhyh4/M5dmc6Wowgd7opBwGxmoweVZxS23HkTnQtTd1T2pu3RdlLp/VFQwfqvCEE8XZ3IGuobFfYiBDYxPSYVewM1H8wYq6DtA9WOZu0Gk1miHZsU9MsvaVeO9Ui8strXnFEeyVakMRSQz0STBWi0XIsZbGZA98h32oDYTl0ynMb4PvRmx/hGKE19RijRbny76eExKUHj5hrBZrwPzrC5vbYCbbc81iWlIYOJI7vN5bZeOclYCR6yCEbqEx/2lSGHg26ORjTLJysOuu5cDApl5oURycJK1RBdlvJk9QmDwftyArboYSLweGYsgblMnxsHzOYBZegPU0t2jxhl4qjFI+tIpiyWc6NVO7SGOyR6lVWmBiLWL5TFVIpVbpi/knKVZ4VinJyIqbodRLgaEZsgNeHUq6BrP9ORpzWxQohb1QOqUktVJrhFFsccwkaQ3FkE+um1x8ihabnuE/XNyqZDDQIgNDEr4UrztVNBae0rPc1kRJ+mL7AwZsfzMVGD0WMqhi7sFEugaGfwa2yH8wUJVEZ1522nQpwCyOT94qaJGWp6EO7oLsRuXld2uBgZYasPCBFt0YNBSDMvHbaEbMphnhxaRuJjv1sgUSwVBmfhfEa9JlxGzg0BVWee+31Hf93jYyJSXz8mD/TJ7NuV0LjMEqvlQR6L+kRddS3/VbrsV9XyyxoMrb2nZ6+nLkoiSpeefo7F9FnO9YKnumkG86DnmJdNtOT88dqva2xPWtoNoXbfp2fFYpoXV46nKhvfkMQmhtKjCZHLeukG/+oW341ytKurBeQVVj3A3+a/mHaxHr6Rjl2o5OuSNn/uDaB6dLPF3n8mye6uW8m/LL6zhLw1fnuNDgNOhWtR+dYt3hUWOZO1+XzBByrM9jnekIcRsTxaXye+aaLrfxmm78P40VMVN1o9kT6peaugalulBEKua8Jt1qGCO0vBM5eXZW/u8gcvLsnyVi09s3Haa2tS8iB1lwd2vfNzcFwMx736r0tFsr6ttLg1+fOK8EE+g9fh6uQxwWfBk3BAQLvoyBUz9dUgJQ84Hvf5zBQnPcy3VFzFoTrO4eHJ5N1SFvxWFoU+UG2iI8mrKbKjdoXeQfimy/d0MyDkoAAAAASUVORK5CYII=">
            </i>
            <p>
              Auditoría
            </p>
          </a>
        </li>
                        <li class="nav-item">
          <a href="/cartera-de-usuarios" id="menu_usuarios" class="nav-link">
            <i style="padding-right:15px;">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAACQElEQVR4nGNgGAWDBER3HjqZMeXsUWQc333stF/pBim6OyZn5sUH3ds//EfGpYvuvPcv365Ac8szS7r0Uit77VOq+g1AOHvmhWfojilZdPsDRY6JaT94IHXymT3YcEznoQ0wdSmVfWtSK/vPpVb2bwDhnGlnvqI7pmzh7U8UOSYHS3DDcNb08+dg6lIr+xtSqvoTaBpNOWQ6JmPK2Sct61/+R8aFc699JMkxaeUTbNLK+oPSy/sdQDh7+vkXuByTOfnUbZi6tIr+CciOCW3aHxPecjABGYc1HYjzqd/ERbRjUiv7VqRW9B9Jq+xfAMLZ005/wemYScffwtSB9CE7hioglYi4JyaaotsP3syafuE6Mo7vOX7bp3yTHN0dk0ONBJyKZmhCz9HHIEOw4YSe45fh+ir6p6RW9U9Kq+wPAOHs6edfYThm4W1IAo6eVSZTtbHncsee6eew4eJVHRuwOQakGRcOLNsmA1OXVt5bk17etwKUkEE4e9qZT5iOuQNxTPz8coVZJ5e+3/Vg739suHnH5D3YHEMuwBtN9HZMcv/Jp9XLH/xCxtnTLxLnmIaNk6+CHJFa2bc/paq/k1LH+FdtMcCGQ+tXsRF0TN3ayfdAoZJa0f8gpaJvPQMtAbHRlFLRNyO1uq9sQEOmmd5ppmpD9+Oe/TMfYMPFq9sOUNMxNCn0/GlRzgyqEnhY1E05A11rp1b2zxsU7Zn08n6FtPIem0HR0htUbeBB3TuIGUz9pkHVoxySfe1BPQrBMFQAACGOOqoIeCanAAAAAElFTkSuQmCC">
            </i>
            <p>
              Usuarios
            </p>
          </a>
        </li>
                <li class="nav-item">
          <a href="/configuracion" id="menu_configuracion" class="nav-link">
            <i style="padding-right:15px;">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE5UlEQVR4nMWYy08bZxDAKW0qtVTtpUoaqUql3npuz63UvyCH9lhVveTWQ4VUUhK6qMVg42BssLHXD/wOsMWxdzF+PzDrR2womEceGDBguw40EU2VEELAnuqjsmObtdeBhIw0Wn3rne/77cx8M5+3oeEEwm3RXhBcVn1aUDRueB3Cb9Y1aXodadI49big6l5bRowR7506jAQbvuimbuUiEykoqItczIkx4ptTh1F2k4qwf6MIghSN5RyL7lRBoAHe0Pe7J0pBCqoW2MIYhjW+cgjhL9fP4RxSq+m133GaF3aZYNzkrV292D2Pd1k0op/1H790CAzzvyXrNAuH8IkM7Vo9AsCkQe8aEIrJjIxzQ4JhxNvHWBR/V9ZpmZR1WtR92PWvEcQ1zPihvJuK+qx3y5K1oNFAGqaCmcMr0+9+eyKn7LZGBa3688S3xJuiNuNXim6rEOdSXm4L/kFVGLyLlAYcKxDyrcP4yMxTg9izpBU5l0PetbIF4rEsrCW24e/sDmzf3y0qGq8l/oF47F7Z82g+g9h7m1DScyiM6B5aR9ph1jCC8K+MfvKHis7Ucv0UnYaN1X/LAKppKvkQYsFMzVAO44FNydWhz47AKHhjtrBvvarhn5EsbP31uC6QUk/NRLJV5wx51wHvIn0VuYI1KnhjgaAnyWgUo9OwlX0xkCLQvR2YCjF7aNK1ArIO8zR+CT9THqZmXZOKP+6jGYA26gxNrZBVzhlwrgDeSUZFP+rfZ8ybnp+IdxR8yj3pWsmXJmu1RR5s7sBcNAlh9+LhFY2rPRuPPk/qgH05L+VYwqx9TNCq/cJumtsrGKJdwzT5+vJ9GDPSS7YR+nunKX4WXSkDnUD3mZ5H8xTmdIzG93qvGL5sYBNZl+Vqae5Ubt+CRxAIk/2YkV5+sPWEMZmLBdGTBCnH8hsrzGCPVVxa0JjeEoXETtz8jsmeGg79MB9NMtqVFkYll8JZYdQ9DkWxrgQzjJOGXAtgN02fZ7JH99HvTHaldUd1bZy9uw8KbCI2z6A3RzlS1TMxds8oeJScFUbeRbaiJseWMyhZK20Bg8YxY5A9Z7xrMPC7uYMVRtRm/Nxpni/upmTt3bSMPOEh5891SobSmFCbb+/TQXufnkF1gIme669C7bM2oWavoK2Cwb0jdQbnkpMT9kSxzszWqjNbTw5DhuqMQGWCy3zlibSsAit4VAhVxkhFtUzVUYH7NJaXA4N6k7yLDKNewdibgmnYZGmSOrMHCFvg2CoYND0qekbFHycrD9iRyq5do1n6QnNwEnEHZ9aLMMIW4oJJE07VOn/E6DSkVh++epjDbc2z9B+ecf0pcFkWd0fkgYUhqX8x5Cv32Gw0C8mlbdgq2fb1whzkDsAQoeDR053aMOgMLOeNuRQ8q7hwBuZjurMqvi044UhUPQOjykrZY0cWzufzZeP93AGI3Dogpuzsnqkm+CX8jJRj6R1VB1PodMYERTLAKAIE3FyN/w9ysA8ClxrMM+76wsQmYoz4SMm14sYB76yHur3LBoNC0U72g/dOBPgOFVjn/FXD98Iwpf8oDRKPlw0Gye6zPeDacHAs0lVBTgSDRNFtldYDgySXz9UEOTGMBBu+6Bu/e1APTD1yIhh+s65JK3SmCt9miBv0/muDQVL65WpAa7a76OkMmvQ4KtGT6/8BiSLWgRkGE0MAAAAASUVORK5CYII=">
            </i>
            <p>
              Configuración
              <!--span class="right badge badge-danger">New</span-->
            </p>
          </a>
        </li>
                            <!-- Botón para rol de Vendedor -->
            
    </ul>
  </nav>
</div>
				<!-- /.sidebar -->
			</aside>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
	<div class="col-sm-6">
		<h1 class="m-0 text-dark txt-title"></h1>
	</div>
	<div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="/mi-local#">Mi local</a></li>
		</ol>
	</div>
</div>
					</div><!-- /.container-fluid -->
				</div>
				<!-- /.content-header -->
				<!-- Main content -->
				<section class="content">
					<div class="container-fluid">

												
												
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        Nota de crédito vencida del cliente Marco López  por un total de: 15410 menos 6500 aboado. Venta de Administrador
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

<!-- Modal Crear -->
<div class="modal fade" id="modal_crear">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registro de Vendedor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_solicitud_pv">
          <div class="row">
            <!-- Sección: Datos de la Bodega -->
            <!--div class="col-12">
              <h4 class="text-primary">Datos de la Bodega</h4>
              <hr>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-label" for="nombre">Nombre de la bodega</label>
                <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre de la bodega" autocomplete="off" required>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-label" for="estado">Estado</label>
                <input class="form-control" name="estado" id="estado" type="text" placeholder="Nombre del Estado" required>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-label" for="ciudad">Ciudad o localidad</label>
                <input class="form-control" name="ciudad" id="ciudad" type="text" placeholder="Nombre de la ciudad" required>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-label" for="cp">Código Postal</label>
                <input class="form-control" name="cp" id="cp" type="text" placeholder="C.P." required>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-label" for="direccion">Dirección</label>
                <input class="form-control" name="direccion" id="direccion" type="text" placeholder="Dirección" required>
              </div>
            </div-->

            <!-- Sección: Datos del Encargado -->
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label" for="name">Usuario encargado</label>
                <input class="form-control" name="name" id="name" type="text" placeholder="Nombre del encargado" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label" for="telefono">Número de celular</label>
                <input class="form-control" name="telefono" id="telefono" type="text" placeholder="Número Celular" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label" for="correo">Correo electrónico</label>
                <input class="form-control" name="correo" id="correo" type="email" placeholder="Correo electrónico" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="form-label" for="password">Genere una contraseña</label>
                <input class="form-control" name="password" id="password" type="password" placeholder="**********" required>
              </div>
            </div>
          </div>

          <div class="form-group mt-3">
            <button type="submit" class="btn btn-success" id="btn_solicitar_pv">Registrar cuenta</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<!-- Modal Dar de Baja -->
<div class="modal fade" id="modal_baja">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Dar de Baja Local</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_baja_local">
          <div class="form-group">
            <label for="m_baja">Motivo de Baja</label>
            <input type="text" class="form-control" name="m_baja" id="m_baja" required>
          </div>
          <input type="hidden" name="id" id="local_id">
          <div class="form-group mt-3">
            <button type="submit" class="btn btn-danger" id="btn_baja_local">Dar de Baja</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Info boxes -->
<div class="row">
		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a href="/punto-de-venta" class="btn btn-default btn-span">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAADIklEQVR4nO2czU8TURTFZ4V/hLr2P3BtaAOFypItK9Npa1A3FGemTfdl6kxbd9BS4rbAhOBCEkwGo+XLRBO/gpvZycponNiSLp65NSRQI0pf6XtTzknOBkrmzq/nzntNeVdRICiwmp4uXInrhQfJTOmjqttHMc1iMptqpFoTafs+1S4UnqrnrybSxc+aueRXVl+w2vO3zHHfSW2qsby6xXSz6ieN4gHdgxB49O4RPLOy1hINpVub5bUWQRSSRGpbSp4jAQgea+bSj7hevNd3gMlM6RO1rSMBBB4vrGyxu5nSh74DVDX7aDkAzzznH65tvqGFpdl3gLSiib55p0eme7l5a6JtVbMb8bT9LJ62bgCge36A+fllljarLVWzvicM87qwBE4tpKTy/wIsLjptp/OLrbhReCoU4Ia3KYW7AUhJjD20GgDodQeQTD8HQA8AmYgWRgI9AGQiFxEk0ANAhgR64veAaGHvEgOcksiBA+gEyADoAiBDAl3xrThQLdypfv++YVmnDIAuADIkEC3cO2EfyCkA5BQAcgoAB3wfyL4+OWXsA10AZEggWrh3wiLCKQDkFAByCgAHfB+40fHNHPaBLgAyJBAt3DthEeEUAHIKADkFgAO+D+wU9oEuALIgJfDRfI3FdPsnb6de2hY2zPZRr3VhAJ0A+SRASh7BUw37251U4RoAuucDSOfj4oa9fqHwSHRIeYAOXDcUEUf+afqFIwGEQB75p7krNDrEkQACj7Vc1RcydIJGhdDIELMc4LEnld9jTyaz2SFFhGhoTdIoHtDoEGqFIDwTqUaqlZIndPDOsSaz2SFqAXqOqLrVlH/0k9VMZh6/p5qFJe8s1UOhP/4/+SJ8vO3o9Fl/Q7UpsqsOgAAoVHUkEACFqo4EAqBQ1ZFAABSqOhIIgEK1HQ43/bk56T7KUU1UmyK7diKR14ezs9IBPEyl2HYksqfIrlfDwxP70ajv53LSAKRa9sbH/e1wOKoEQbujo+bu2Fjjy8xMu3VEAaRrU/II3s7ISE4Jkl6GQrd3IpF9eu7Qytxr/w3gydfQtaltA5M8CFK60S+BZPxpOBGv5wAAAABJRU5ErkJggg==">
					<!--img style="width: 80px;" src="data:image/png;base64,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"/-->
					<br>
					<span class="txt-blue">P. Venta</span>
				</a>
				<a href="/caja-registradora" class="btn btn-default btn-span">
					<img src="data:image/png;base64,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">
					<!--img style="width: 80px;" src="data:image/png;base64,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"/-->
					<br>
					<span class="txt-blue">Caja</span>
				</a>
				<a href="/mi-local/productos" class="btn btn-default btn-span">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAACrUlEQVR4nO2YT2sTURTF8+FkSlfdWj+Ai1jQlcQkHQzpTJo0mdAWom1RN1ootoIRg2ijlVptS3OFgn8QQdBSilUr6qxcXLnTVtG0fcVJeMm8c+FskvMewy/nvncnsRgKhYpCTaStjSm7ZxvqUTIQVk0AK8kTvLWYhhbVDITVgQB9ciBSMwBAChcUACQAZJ3HDRJIAMhIIEWwhV9Uz0FVNYMDAU7aVvVqpvfBlYu9P++Mn+RapR+q/GEgTISNMBJWh77STQ5aX7eXbO1t4neYhImwUb4TA6ADgD4S6GhvWbQwASDrTl3HXiLf14b43qVTPJG2gpkqjGQPGTO+rWXNAbh+ayA0uH/1fPaMOQAbN+MtByh7GgNwZznD08N9LYM3ne/jnZWMOQB9OQcbQ7y5kOT39UQoyR6yV7uft+MA+l0mACQAZCSQ9LciWpgAkHWnCZcIASDrThTGGAJA7iZhkCYAZCSQ9LciWpgAkHWnCZcIASDrThTGGAJA7iZhkCYAZCSQ9LciWpgAkHWnCZcIHQ1h44nbcq8xt/CnZZcTWY8fzuWVXvGcz3r88ZkaohEAfzQc9kZLfPpCmeOpMr+pHw7m7aMcx1Ne4B0pF4O1xgOszRQCIPtKOR5/XmkG82XV5bS7C29ftZm82QncXHLZLZY46ewm8KztcbZQ4ts3Ck1e+Uy+E494ZY2slT2MBejvafXucABldKyo9IpHvLJG5QVAAkBGAgktrP2M83EGOgCoO2U+bmEHADtVW0/dYK57NZ9TesUjXlmDOZDa+8NEfpBev5/ja1MjoXTUG0nkAc7P5v/6c+B/NHe9YC7Ad4/dAGIYva7nzAXot1kASADISCDpb0W0MAEgR/4S+VBPBAsg+zcDYXIsgJcHrZdihKwmBsJGCRCFQqFQqNgx6xenztHu7V06BwAAAABJRU5ErkJggg==">
					<!--img style="width: 80px;" src="data:image/png;base64,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"/-->
					<br>
					<span class="txt-blue">Productos</span>
				</a>
								<button type="button" class="btn-span btn btn-default" data-toggle="modal" data-target="#modal_crear">
					<img src="data:image/png;base64,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" alt="add-user-male">
					<br>
					<span class="txt-blue">Nuevo vendedor</span>
				</button>
												<a href="/configuracion" class="btn btn-default btn-span">
					<img style="width: 80px;" src="data:image/png;base64,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" alt="settings--v1">
					<br>
					<span class="txt-blue">Config.</span>
				</a>
							</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			</div>
		</div>
	</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<br>
					<p><b>VENDEDORES VINCULADOS</b></p>
				

			
				<a href="/mi-local#" class="btn btn-default btn-span" data-toggle="modal" data-target="#modal_baja" data-id="2">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nO2cbUxbVRjHTzC0mukn5kuiMTFmH3VvLmyfljA1GdkiuOgH/EA00yzGMDQzflkys8WEjalkY2aAgdaMWVpWYOVlQFv6cutoLS19R6iJygaGSFQSMtHBY87V3o3V3nvovbtF7vMk/5Tee87/PueXc58+vRAIwcDAwMDAwMDAwBCJseTUnnAqbQxPpM0bWqm0MZic3K34Zgil0tPhiTRoQaFU+ifFARZ6UWGVdd8BjiUnwRkIgzcUz7q4PzYBDn8I/LFU1jk6ns4LJSe1C5ACarL0QcMlK6+OQZdwzj4agnOXu/nj9JW+z5yj4zJz6Hzqo0mAVxycAKLhX2V2YpOld9VxCuqfnRfLmmN1cNoEaB50Z8FwBsb5cxe+vrrqeKOphz8+EhjPmkN9NAnQFYxAQ3uXAKK5s0+oaZ3DnlWQOoe9Qs2k44Rz7V3gDka1CTADseOaC7ocHATid2pZKDUF/d4AmAZG+Ff6PnOO1jx629J56xmeKgA3uggCTCPA8EbZgTBSvA3cehBTu/0VOG4ryynOXSE6n0ps/t2S8qHXEptPc5XyoGtWDqBb3yB2sdueEjjRu0806QRXrhpAei2x+TTX294ScR+X/nNl4AVJMbh0c2IXm+N2Sy76hm+vagCnub2SHjRncR/dLxAnOvkAXbpKqYTHvAclE/6N26oawF+5bZIeIc9B6dvYo6tQAKC+W279O24rgyXvk6oBXPI+JelxmaUOuvRd8uD5Nj0GLv2fcuvfqd4XAdwPqQaQXoteU3YddOv/Avumx/MH6NG9L5XsHEP9axliuF0UBaiHpsEDCtRBvhbW5g/QpR9Xov71OCtUB9jlrFSmDrr10TzhFW9nSbSdof6x9IBKA5TqBZnrYL49oVTvJ9Q/m/SCWXpApQFK9YJ8HbSVsdTBtfeELL0f1YxnJ9OCf/aWqg5w1lPK5DXr2cngt8aekKX3oxoa2MGU5JJjs+oAlxyPMnkN9+9gu43X0hOCQ+dhMT3X/QJTkivOB1UHSK/J4tXYs4sNoF3nZgdoKVoASxGIaaVTB+80l8JbTbtEdbh5Dz+WxY+OVduProHFDyxFv7MDbCPfgYGAmG51PAtHLpbDa/VbRfXuxQP8WGm/LQXz+8P0jKQfGEiKHaCBnJQynLXXQKPttGSCX9jOwIzjA8kEZxzHCuY3a39PGmAb+Zgd4JfkETCQRC6zRdMWiCSj8G0iCTUtr+ZMrqblEAQTSYgk47BoeS5ncoudz0MkmSicXyICiyaRXd1GYmAmDzMD5CG2k81gIGYwkOWM0YqhCG6Y94E/6BOe3I7GonDSdBReP7tdSIz+fMpUC/54TBgXjYdhvq8SwFgkJLZifADm+w5BNDFecL9AkIOb5jJ+jXfBW4Y2YgIjKVkTvFUg28gT0ErKr/d/CgPDNuh2+sAXTmQ9AuciY3DFY+Hli9z5a4R7FYv54fvRVl6xWCDnOLX96Jro2gaGr8L1vrMU3n5oIfk/SLg3qHlG/wUw/D9XBmBGioFDgPd5B7oCYTBaexVRrt0RmfoBkj/OKi7qW/AdaLT2wktVhxVRLoB0sdMLy4qL+moKYL13Aaot87JFfTQJsNoyD0/X3ZQt6oMA6xAg4A6cwFsYa+AEfogAfohU4acwYBuDfSBgI12HjTRo/pvI/uoj8PIbb+NXuXy/C59pNUHtJ58hwHwBNlyywkf1FxDgegR4beoWGEOLskV9NAlwWgsPVI+dPg9vfnhCUYAb+pdKRhUeqCLAKgSIO1Bu4C0sMxCgzECAMgMBKgiQ/i+EoW+CvKxDbjj/VYciyngWQnRNqvWBWhBBgL71BXDAG5jWkggGBgYGBgYGBgYGyR1/A9+bnnmANDG3AAAAAElFTkSuQmCC">
					<br>
					<span class="text-dark">ERIK CORCINO HERNANDEZ</span>
				</a>
			
				<a href="/mi-local#" class="btn btn-default btn-span" data-toggle="modal" data-target="#modal_baja" data-id="8">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nO2cbUxbVRjHTzC0mukn5kuiMTFmH3VvLmyfljA1GdkiuOgH/EA00yzGMDQzflkys8WEjalkY2aAgdaMWVpWYOVlQFv6cutoLS19R6iJygaGSFQSMtHBY87V3o3V3nvovbtF7vMk/5Tee87/PueXc58+vRAIwcDAwMDAwMDAwBCJseTUnnAqbQxPpM0bWqm0MZic3K34Zgil0tPhiTRoQaFU+ifFARZ6UWGVdd8BjiUnwRkIgzcUz7q4PzYBDn8I/LFU1jk6ns4LJSe1C5ACarL0QcMlK6+OQZdwzj4agnOXu/nj9JW+z5yj4zJz6Hzqo0mAVxycAKLhX2V2YpOld9VxCuqfnRfLmmN1cNoEaB50Z8FwBsb5cxe+vrrqeKOphz8+EhjPmkN9NAnQFYxAQ3uXAKK5s0+oaZ3DnlWQOoe9Qs2k44Rz7V3gDka1CTADseOaC7ocHATid2pZKDUF/d4AmAZG+Ff6PnOO1jx629J56xmeKgA3uggCTCPA8EbZgTBSvA3cehBTu/0VOG4ryynOXSE6n0ps/t2S8qHXEptPc5XyoGtWDqBb3yB2sdueEjjRu0806QRXrhpAei2x+TTX294ScR+X/nNl4AVJMbh0c2IXm+N2Sy76hm+vagCnub2SHjRncR/dLxAnOvkAXbpKqYTHvAclE/6N26oawF+5bZIeIc9B6dvYo6tQAKC+W279O24rgyXvk6oBXPI+JelxmaUOuvRd8uD5Nj0GLv2fcuvfqd4XAdwPqQaQXoteU3YddOv/Avumx/MH6NG9L5XsHEP9axliuF0UBaiHpsEDCtRBvhbW5g/QpR9Xov71OCtUB9jlrFSmDrr10TzhFW9nSbSdof6x9IBKA5TqBZnrYL49oVTvJ9Q/m/SCWXpApQFK9YJ8HbSVsdTBtfeELL0f1YxnJ9OCf/aWqg5w1lPK5DXr2cngt8aekKX3oxoa2MGU5JJjs+oAlxyPMnkN9+9gu43X0hOCQ+dhMT3X/QJTkivOB1UHSK/J4tXYs4sNoF3nZgdoKVoASxGIaaVTB+80l8JbTbtEdbh5Dz+WxY+OVduProHFDyxFv7MDbCPfgYGAmG51PAtHLpbDa/VbRfXuxQP8WGm/LQXz+8P0jKQfGEiKHaCBnJQynLXXQKPttGSCX9jOwIzjA8kEZxzHCuY3a39PGmAb+Zgd4JfkETCQRC6zRdMWiCSj8G0iCTUtr+ZMrqblEAQTSYgk47BoeS5ncoudz0MkmSicXyICiyaRXd1GYmAmDzMD5CG2k81gIGYwkOWM0YqhCG6Y94E/6BOe3I7GonDSdBReP7tdSIz+fMpUC/54TBgXjYdhvq8SwFgkJLZifADm+w5BNDFecL9AkIOb5jJ+jXfBW4Y2YgIjKVkTvFUg28gT0ErKr/d/CgPDNuh2+sAXTmQ9AuciY3DFY+Hli9z5a4R7FYv54fvRVl6xWCDnOLX96Jro2gaGr8L1vrMU3n5oIfk/SLg3qHlG/wUw/D9XBmBGioFDgPd5B7oCYTBaexVRrt0RmfoBkj/OKi7qW/AdaLT2wktVhxVRLoB0sdMLy4qL+moKYL13Aaot87JFfTQJsNoyD0/X3ZQt6oMA6xAg4A6cwFsYa+AEfogAfohU4acwYBuDfSBgI12HjTRo/pvI/uoj8PIbb+NXuXy/C59pNUHtJ58hwHwBNlyywkf1FxDgegR4beoWGEOLskV9NAlwWgsPVI+dPg9vfnhCUYAb+pdKRhUeqCLAKgSIO1Bu4C0sMxCgzECAMgMBKgiQ/i+EoW+CvKxDbjj/VYciyngWQnRNqvWBWhBBgL71BXDAG5jWkggGBgYGBgYGBgYGyR1/A9+bnnmANDG3AAAAAElFTkSuQmCC">
					<br>
					<span class="text-dark">IGNACIO GARCIA MENESES</span>
				</a>
			
				<a href="/mi-local#" class="btn btn-default btn-span" data-toggle="modal" data-target="#modal_baja" data-id="10">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nO2cbUxbVRjHTzC0mukn5kuiMTFmH3VvLmyfljA1GdkiuOgH/EA00yzGMDQzflkys8WEjalkY2aAgdaMWVpWYOVlQFv6cutoLS19R6iJygaGSFQSMtHBY87V3o3V3nvovbtF7vMk/5Tee87/PueXc58+vRAIwcDAwMDAwMDAwBCJseTUnnAqbQxPpM0bWqm0MZic3K34Zgil0tPhiTRoQaFU+ifFARZ6UWGVdd8BjiUnwRkIgzcUz7q4PzYBDn8I/LFU1jk6ns4LJSe1C5ACarL0QcMlK6+OQZdwzj4agnOXu/nj9JW+z5yj4zJz6Hzqo0mAVxycAKLhX2V2YpOld9VxCuqfnRfLmmN1cNoEaB50Z8FwBsb5cxe+vrrqeKOphz8+EhjPmkN9NAnQFYxAQ3uXAKK5s0+oaZ3DnlWQOoe9Qs2k44Rz7V3gDka1CTADseOaC7ocHATid2pZKDUF/d4AmAZG+Ff6PnOO1jx629J56xmeKgA3uggCTCPA8EbZgTBSvA3cehBTu/0VOG4ryynOXSE6n0ps/t2S8qHXEptPc5XyoGtWDqBb3yB2sdueEjjRu0806QRXrhpAei2x+TTX294ScR+X/nNl4AVJMbh0c2IXm+N2Sy76hm+vagCnub2SHjRncR/dLxAnOvkAXbpKqYTHvAclE/6N26oawF+5bZIeIc9B6dvYo6tQAKC+W279O24rgyXvk6oBXPI+JelxmaUOuvRd8uD5Nj0GLv2fcuvfqd4XAdwPqQaQXoteU3YddOv/Avumx/MH6NG9L5XsHEP9axliuF0UBaiHpsEDCtRBvhbW5g/QpR9Xov71OCtUB9jlrFSmDrr10TzhFW9nSbSdof6x9IBKA5TqBZnrYL49oVTvJ9Q/m/SCWXpApQFK9YJ8HbSVsdTBtfeELL0f1YxnJ9OCf/aWqg5w1lPK5DXr2cngt8aekKX3oxoa2MGU5JJjs+oAlxyPMnkN9+9gu43X0hOCQ+dhMT3X/QJTkivOB1UHSK/J4tXYs4sNoF3nZgdoKVoASxGIaaVTB+80l8JbTbtEdbh5Dz+WxY+OVduProHFDyxFv7MDbCPfgYGAmG51PAtHLpbDa/VbRfXuxQP8WGm/LQXz+8P0jKQfGEiKHaCBnJQynLXXQKPttGSCX9jOwIzjA8kEZxzHCuY3a39PGmAb+Zgd4JfkETCQRC6zRdMWiCSj8G0iCTUtr+ZMrqblEAQTSYgk47BoeS5ncoudz0MkmSicXyICiyaRXd1GYmAmDzMD5CG2k81gIGYwkOWM0YqhCG6Y94E/6BOe3I7GonDSdBReP7tdSIz+fMpUC/54TBgXjYdhvq8SwFgkJLZifADm+w5BNDFecL9AkIOb5jJ+jXfBW4Y2YgIjKVkTvFUg28gT0ErKr/d/CgPDNuh2+sAXTmQ9AuciY3DFY+Hli9z5a4R7FYv54fvRVl6xWCDnOLX96Jro2gaGr8L1vrMU3n5oIfk/SLg3qHlG/wUw/D9XBmBGioFDgPd5B7oCYTBaexVRrt0RmfoBkj/OKi7qW/AdaLT2wktVhxVRLoB0sdMLy4qL+moKYL13Aaot87JFfTQJsNoyD0/X3ZQt6oMA6xAg4A6cwFsYa+AEfogAfohU4acwYBuDfSBgI12HjTRo/pvI/uoj8PIbb+NXuXy/C59pNUHtJ58hwHwBNlyywkf1FxDgegR4beoWGEOLskV9NAlwWgsPVI+dPg9vfnhCUYAb+pdKRhUeqCLAKgSIO1Bu4C0sMxCgzECAMgMBKgiQ/i+EoW+CvKxDbjj/VYciyngWQnRNqvWBWhBBgL71BXDAG5jWkggGBgYGBgYGBgYGyR1/A9+bnnmANDG3AAAAAElFTkSuQmCC">
					<br>
					<span class="text-dark">HUGO NAVARRETE VEGA</span>
				</a>
			
				<a href="/mi-local#" class="btn btn-default btn-span" data-toggle="modal" data-target="#modal_baja" data-id="11">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nO2cbUxbVRjHTzC0mukn5kuiMTFmH3VvLmyfljA1GdkiuOgH/EA00yzGMDQzflkys8WEjalkY2aAgdaMWVpWYOVlQFv6cutoLS19R6iJygaGSFQSMtHBY87V3o3V3nvovbtF7vMk/5Tee87/PueXc58+vRAIwcDAwMDAwMDAwBCJseTUnnAqbQxPpM0bWqm0MZic3K34Zgil0tPhiTRoQaFU+ifFARZ6UWGVdd8BjiUnwRkIgzcUz7q4PzYBDn8I/LFU1jk6ns4LJSe1C5ACarL0QcMlK6+OQZdwzj4agnOXu/nj9JW+z5yj4zJz6Hzqo0mAVxycAKLhX2V2YpOld9VxCuqfnRfLmmN1cNoEaB50Z8FwBsb5cxe+vrrqeKOphz8+EhjPmkN9NAnQFYxAQ3uXAKK5s0+oaZ3DnlWQOoe9Qs2k44Rz7V3gDka1CTADseOaC7ocHATid2pZKDUF/d4AmAZG+Ff6PnOO1jx629J56xmeKgA3uggCTCPA8EbZgTBSvA3cehBTu/0VOG4ryynOXSE6n0ps/t2S8qHXEptPc5XyoGtWDqBb3yB2sdueEjjRu0806QRXrhpAei2x+TTX294ScR+X/nNl4AVJMbh0c2IXm+N2Sy76hm+vagCnub2SHjRncR/dLxAnOvkAXbpKqYTHvAclE/6N26oawF+5bZIeIc9B6dvYo6tQAKC+W279O24rgyXvk6oBXPI+JelxmaUOuvRd8uD5Nj0GLv2fcuvfqd4XAdwPqQaQXoteU3YddOv/Avumx/MH6NG9L5XsHEP9axliuF0UBaiHpsEDCtRBvhbW5g/QpR9Xov71OCtUB9jlrFSmDrr10TzhFW9nSbSdof6x9IBKA5TqBZnrYL49oVTvJ9Q/m/SCWXpApQFK9YJ8HbSVsdTBtfeELL0f1YxnJ9OCf/aWqg5w1lPK5DXr2cngt8aekKX3oxoa2MGU5JJjs+oAlxyPMnkN9+9gu43X0hOCQ+dhMT3X/QJTkivOB1UHSK/J4tXYs4sNoF3nZgdoKVoASxGIaaVTB+80l8JbTbtEdbh5Dz+WxY+OVduProHFDyxFv7MDbCPfgYGAmG51PAtHLpbDa/VbRfXuxQP8WGm/LQXz+8P0jKQfGEiKHaCBnJQynLXXQKPttGSCX9jOwIzjA8kEZxzHCuY3a39PGmAb+Zgd4JfkETCQRC6zRdMWiCSj8G0iCTUtr+ZMrqblEAQTSYgk47BoeS5ncoudz0MkmSicXyICiyaRXd1GYmAmDzMD5CG2k81gIGYwkOWM0YqhCG6Y94E/6BOe3I7GonDSdBReP7tdSIz+fMpUC/54TBgXjYdhvq8SwFgkJLZifADm+w5BNDFecL9AkIOb5jJ+jXfBW4Y2YgIjKVkTvFUg28gT0ErKr/d/CgPDNuh2+sAXTmQ9AuciY3DFY+Hli9z5a4R7FYv54fvRVl6xWCDnOLX96Jro2gaGr8L1vrMU3n5oIfk/SLg3qHlG/wUw/D9XBmBGioFDgPd5B7oCYTBaexVRrt0RmfoBkj/OKi7qW/AdaLT2wktVhxVRLoB0sdMLy4qL+moKYL13Aaot87JFfTQJsNoyD0/X3ZQt6oMA6xAg4A6cwFsYa+AEfogAfohU4acwYBuDfSBgI12HjTRo/pvI/uoj8PIbb+NXuXy/C59pNUHtJ58hwHwBNlyywkf1FxDgegR4beoWGEOLskV9NAlwWgsPVI+dPg9vfnhCUYAb+pdKRhUeqCLAKgSIO1Bu4C0sMxCgzECAMgMBKgiQ/i+EoW+CvKxDbjj/VYciyngWQnRNqvWBWhBBgL71BXDAG5jWkggGBgYGBgYGBgYGyR1/A9+bnnmANDG3AAAAAElFTkSuQmCC">
					<br>
					<span class="text-dark">MIGUEL DIONICIO FLORES</span>
				</a>
			
				<a href="/mi-local#" class="btn btn-default btn-span" data-toggle="modal" data-target="#modal_baja" data-id="12">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nO2cbUxbVRjHTzC0mukn5kuiMTFmH3VvLmyfljA1GdkiuOgH/EA00yzGMDQzflkys8WEjalkY2aAgdaMWVpWYOVlQFv6cutoLS19R6iJygaGSFQSMtHBY87V3o3V3nvovbtF7vMk/5Tee87/PueXc58+vRAIwcDAwMDAwMDAwBCJseTUnnAqbQxPpM0bWqm0MZic3K34Zgil0tPhiTRoQaFU+ifFARZ6UWGVdd8BjiUnwRkIgzcUz7q4PzYBDn8I/LFU1jk6ns4LJSe1C5ACarL0QcMlK6+OQZdwzj4agnOXu/nj9JW+z5yj4zJz6Hzqo0mAVxycAKLhX2V2YpOld9VxCuqfnRfLmmN1cNoEaB50Z8FwBsb5cxe+vrrqeKOphz8+EhjPmkN9NAnQFYxAQ3uXAKK5s0+oaZ3DnlWQOoe9Qs2k44Rz7V3gDka1CTADseOaC7ocHATid2pZKDUF/d4AmAZG+Ff6PnOO1jx629J56xmeKgA3uggCTCPA8EbZgTBSvA3cehBTu/0VOG4ryynOXSE6n0ps/t2S8qHXEptPc5XyoGtWDqBb3yB2sdueEjjRu0806QRXrhpAei2x+TTX294ScR+X/nNl4AVJMbh0c2IXm+N2Sy76hm+vagCnub2SHjRncR/dLxAnOvkAXbpKqYTHvAclE/6N26oawF+5bZIeIc9B6dvYo6tQAKC+W279O24rgyXvk6oBXPI+JelxmaUOuvRd8uD5Nj0GLv2fcuvfqd4XAdwPqQaQXoteU3YddOv/Avumx/MH6NG9L5XsHEP9axliuF0UBaiHpsEDCtRBvhbW5g/QpR9Xov71OCtUB9jlrFSmDrr10TzhFW9nSbSdof6x9IBKA5TqBZnrYL49oVTvJ9Q/m/SCWXpApQFK9YJ8HbSVsdTBtfeELL0f1YxnJ9OCf/aWqg5w1lPK5DXr2cngt8aekKX3oxoa2MGU5JJjs+oAlxyPMnkN9+9gu43X0hOCQ+dhMT3X/QJTkivOB1UHSK/J4tXYs4sNoF3nZgdoKVoASxGIaaVTB+80l8JbTbtEdbh5Dz+WxY+OVduProHFDyxFv7MDbCPfgYGAmG51PAtHLpbDa/VbRfXuxQP8WGm/LQXz+8P0jKQfGEiKHaCBnJQynLXXQKPttGSCX9jOwIzjA8kEZxzHCuY3a39PGmAb+Zgd4JfkETCQRC6zRdMWiCSj8G0iCTUtr+ZMrqblEAQTSYgk47BoeS5ncoudz0MkmSicXyICiyaRXd1GYmAmDzMD5CG2k81gIGYwkOWM0YqhCG6Y94E/6BOe3I7GonDSdBReP7tdSIz+fMpUC/54TBgXjYdhvq8SwFgkJLZifADm+w5BNDFecL9AkIOb5jJ+jXfBW4Y2YgIjKVkTvFUg28gT0ErKr/d/CgPDNuh2+sAXTmQ9AuciY3DFY+Hli9z5a4R7FYv54fvRVl6xWCDnOLX96Jro2gaGr8L1vrMU3n5oIfk/SLg3qHlG/wUw/D9XBmBGioFDgPd5B7oCYTBaexVRrt0RmfoBkj/OKi7qW/AdaLT2wktVhxVRLoB0sdMLy4qL+moKYL13Aaot87JFfTQJsNoyD0/X3ZQt6oMA6xAg4A6cwFsYa+AEfogAfohU4acwYBuDfSBgI12HjTRo/pvI/uoj8PIbb+NXuXy/C59pNUHtJ58hwHwBNlyywkf1FxDgegR4beoWGEOLskV9NAlwWgsPVI+dPg9vfnhCUYAb+pdKRhUeqCLAKgSIO1Bu4C0sMxCgzECAMgMBKgiQ/i+EoW+CvKxDbjj/VYciyngWQnRNqvWBWhBBgL71BXDAG5jWkggGBgYGBgYGBgYGyR1/A9+bnnmANDG3AAAAAElFTkSuQmCC">
					<br>
					<span class="text-dark">MARCOS FERMIN PANIAGUA ROCHA</span>
				</a>
			
				<a href="/mi-local#" class="btn btn-default btn-span" data-toggle="modal" data-target="#modal_baja" data-id="32">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nO2cbUxbVRjHTzC0mukn5kuiMTFmH3VvLmyfljA1GdkiuOgH/EA00yzGMDQzflkys8WEjalkY2aAgdaMWVpWYOVlQFv6cutoLS19R6iJygaGSFQSMtHBY87V3o3V3nvovbtF7vMk/5Tee87/PueXc58+vRAIwcDAwMDAwMDAwBCJseTUnnAqbQxPpM0bWqm0MZic3K34Zgil0tPhiTRoQaFU+ifFARZ6UWGVdd8BjiUnwRkIgzcUz7q4PzYBDn8I/LFU1jk6ns4LJSe1C5ACarL0QcMlK6+OQZdwzj4agnOXu/nj9JW+z5yj4zJz6Hzqo0mAVxycAKLhX2V2YpOld9VxCuqfnRfLmmN1cNoEaB50Z8FwBsb5cxe+vrrqeKOphz8+EhjPmkN9NAnQFYxAQ3uXAKK5s0+oaZ3DnlWQOoe9Qs2k44Rz7V3gDka1CTADseOaC7ocHATid2pZKDUF/d4AmAZG+Ff6PnOO1jx629J56xmeKgA3uggCTCPA8EbZgTBSvA3cehBTu/0VOG4ryynOXSE6n0ps/t2S8qHXEptPc5XyoGtWDqBb3yB2sdueEjjRu0806QRXrhpAei2x+TTX294ScR+X/nNl4AVJMbh0c2IXm+N2Sy76hm+vagCnub2SHjRncR/dLxAnOvkAXbpKqYTHvAclE/6N26oawF+5bZIeIc9B6dvYo6tQAKC+W279O24rgyXvk6oBXPI+JelxmaUOuvRd8uD5Nj0GLv2fcuvfqd4XAdwPqQaQXoteU3YddOv/Avumx/MH6NG9L5XsHEP9axliuF0UBaiHpsEDCtRBvhbW5g/QpR9Xov71OCtUB9jlrFSmDrr10TzhFW9nSbSdof6x9IBKA5TqBZnrYL49oVTvJ9Q/m/SCWXpApQFK9YJ8HbSVsdTBtfeELL0f1YxnJ9OCf/aWqg5w1lPK5DXr2cngt8aekKX3oxoa2MGU5JJjs+oAlxyPMnkN9+9gu43X0hOCQ+dhMT3X/QJTkivOB1UHSK/J4tXYs4sNoF3nZgdoKVoASxGIaaVTB+80l8JbTbtEdbh5Dz+WxY+OVduProHFDyxFv7MDbCPfgYGAmG51PAtHLpbDa/VbRfXuxQP8WGm/LQXz+8P0jKQfGEiKHaCBnJQynLXXQKPttGSCX9jOwIzjA8kEZxzHCuY3a39PGmAb+Zgd4JfkETCQRC6zRdMWiCSj8G0iCTUtr+ZMrqblEAQTSYgk47BoeS5ncoudz0MkmSicXyICiyaRXd1GYmAmDzMD5CG2k81gIGYwkOWM0YqhCG6Y94E/6BOe3I7GonDSdBReP7tdSIz+fMpUC/54TBgXjYdhvq8SwFgkJLZifADm+w5BNDFecL9AkIOb5jJ+jXfBW4Y2YgIjKVkTvFUg28gT0ErKr/d/CgPDNuh2+sAXTmQ9AuciY3DFY+Hli9z5a4R7FYv54fvRVl6xWCDnOLX96Jro2gaGr8L1vrMU3n5oIfk/SLg3qHlG/wUw/D9XBmBGioFDgPd5B7oCYTBaexVRrt0RmfoBkj/OKi7qW/AdaLT2wktVhxVRLoB0sdMLy4qL+moKYL13Aaot87JFfTQJsNoyD0/X3ZQt6oMA6xAg4A6cwFsYa+AEfogAfohU4acwYBuDfSBgI12HjTRo/pvI/uoj8PIbb+NXuXy/C59pNUHtJ58hwHwBNlyywkf1FxDgegR4beoWGEOLskV9NAlwWgsPVI+dPg9vfnhCUYAb+pdKRhUeqCLAKgSIO1Bu4C0sMxCgzECAMgMBKgiQ/i+EoW+CvKxDbjj/VYciyngWQnRNqvWBWhBBgL71BXDAG5jWkggGBgYGBgYGBgYGyR1/A9+bnnmANDG3AAAAAElFTkSuQmCC">
					<br>
					<span class="text-dark">JAIME SANCHEZ MEDINA</span>
				</a>
			
				<a href="/mi-local#" class="btn btn-default btn-span" data-toggle="modal" data-target="#modal_baja" data-id="37">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nO2cbUxbVRjHTzC0mukn5kuiMTFmH3VvLmyfljA1GdkiuOgH/EA00yzGMDQzflkys8WEjalkY2aAgdaMWVpWYOVlQFv6cutoLS19R6iJygaGSFQSMtHBY87V3o3V3nvovbtF7vMk/5Tee87/PueXc58+vRAIwcDAwMDAwMDAwBCJseTUnnAqbQxPpM0bWqm0MZic3K34Zgil0tPhiTRoQaFU+ifFARZ6UWGVdd8BjiUnwRkIgzcUz7q4PzYBDn8I/LFU1jk6ns4LJSe1C5ACarL0QcMlK6+OQZdwzj4agnOXu/nj9JW+z5yj4zJz6Hzqo0mAVxycAKLhX2V2YpOld9VxCuqfnRfLmmN1cNoEaB50Z8FwBsb5cxe+vrrqeKOphz8+EhjPmkN9NAnQFYxAQ3uXAKK5s0+oaZ3DnlWQOoe9Qs2k44Rz7V3gDka1CTADseOaC7ocHATid2pZKDUF/d4AmAZG+Ff6PnOO1jx629J56xmeKgA3uggCTCPA8EbZgTBSvA3cehBTu/0VOG4ryynOXSE6n0ps/t2S8qHXEptPc5XyoGtWDqBb3yB2sdueEjjRu0806QRXrhpAei2x+TTX294ScR+X/nNl4AVJMbh0c2IXm+N2Sy76hm+vagCnub2SHjRncR/dLxAnOvkAXbpKqYTHvAclE/6N26oawF+5bZIeIc9B6dvYo6tQAKC+W279O24rgyXvk6oBXPI+JelxmaUOuvRd8uD5Nj0GLv2fcuvfqd4XAdwPqQaQXoteU3YddOv/Avumx/MH6NG9L5XsHEP9axliuF0UBaiHpsEDCtRBvhbW5g/QpR9Xov71OCtUB9jlrFSmDrr10TzhFW9nSbSdof6x9IBKA5TqBZnrYL49oVTvJ9Q/m/SCWXpApQFK9YJ8HbSVsdTBtfeELL0f1YxnJ9OCf/aWqg5w1lPK5DXr2cngt8aekKX3oxoa2MGU5JJjs+oAlxyPMnkN9+9gu43X0hOCQ+dhMT3X/QJTkivOB1UHSK/J4tXYs4sNoF3nZgdoKVoASxGIaaVTB+80l8JbTbtEdbh5Dz+WxY+OVduProHFDyxFv7MDbCPfgYGAmG51PAtHLpbDa/VbRfXuxQP8WGm/LQXz+8P0jKQfGEiKHaCBnJQynLXXQKPttGSCX9jOwIzjA8kEZxzHCuY3a39PGmAb+Zgd4JfkETCQRC6zRdMWiCSj8G0iCTUtr+ZMrqblEAQTSYgk47BoeS5ncoudz0MkmSicXyICiyaRXd1GYmAmDzMD5CG2k81gIGYwkOWM0YqhCG6Y94E/6BOe3I7GonDSdBReP7tdSIz+fMpUC/54TBgXjYdhvq8SwFgkJLZifADm+w5BNDFecL9AkIOb5jJ+jXfBW4Y2YgIjKVkTvFUg28gT0ErKr/d/CgPDNuh2+sAXTmQ9AuciY3DFY+Hli9z5a4R7FYv54fvRVl6xWCDnOLX96Jro2gaGr8L1vrMU3n5oIfk/SLg3qHlG/wUw/D9XBmBGioFDgPd5B7oCYTBaexVRrt0RmfoBkj/OKi7qW/AdaLT2wktVhxVRLoB0sdMLy4qL+moKYL13Aaot87JFfTQJsNoyD0/X3ZQt6oMA6xAg4A6cwFsYa+AEfogAfohU4acwYBuDfSBgI12HjTRo/pvI/uoj8PIbb+NXuXy/C59pNUHtJ58hwHwBNlyywkf1FxDgegR4beoWGEOLskV9NAlwWgsPVI+dPg9vfnhCUYAb+pdKRhUeqCLAKgSIO1Bu4C0sMxCgzECAMgMBKgiQ/i+EoW+CvKxDbjj/VYciyngWQnRNqvWBWhBBgL71BXDAG5jWkggGBgYGBgYGBgYGyR1/A9+bnnmANDG3AAAAAElFTkSuQmCC">
					<br>
					<span class="text-dark">YAEL PRUEBAS</span>
				</a>
			
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
		
	</div>
</div>
<!-- /.row -->
						<!-- /.row -->
					</div><!--/. container-fluid -->
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>			<!-- /.control-sidebar -->
			<!-- Main Footer -->
			<footer class="main-footer">
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> <a href="/mi-local#" target="_blank">FD3-ACCESORIOS</a> | Icons <a href="https://iconos8.es/">Icons8</a>
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 2.1.0
  </div>
</footer>		</div>
		<!-- ./wrapper -->
		<!-- REQUIRED SCRIPTS -->
		<!--Start of Tawk.to Script-->
		<script type="text/javascript">
			/*var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6646a478981b6c564771483c/1hu1v4u0p';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();*/
		</script>
		<!--End of Tawk.to Script-->
		<!-- jQuery -->
		<script src="plugins/jquery/jquery.min.js"></script>
		<!-- Bootstrap -->
		<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- overlayScrollbars -->
		<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
		<!-- AdminLTE App -->
		<script src="dist/js/adminlte.js"></script>
		<!-- OPTIONAL SCRIPTS -->
		<script src="dist/js/demo.js"></script>
		<!-- PAGE PLUGINS -->
		<!-- jQuery Mapael -->
		<!--script src="/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
	<script src="/plugins/raphael/raphael.min.js"></script>
	<script src="/plugins/jquery-mapael/jquery.mapael.min.js"></script>
	<script src="/plugins/jquery-mapael/maps/usa_states.min.js"></script-->
		<!-- ChartJS -->
		<!--script src="/plugins/chart.js/Chart.min.js"></script-->

		<!-- PAGE SCRIPTS -->
		<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
		<script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.js"></script>

		<script src="plugins/moment/moment.min.js"></script>
		<script src="plugins/moment/locale/es.js"></script>
		<script src="toast/javascript/jquery.toastmessage.js"></script>

		<script>
	$(document).ready(function() {
		$('#menu_local').attr('class', 'nav-link active');
	});

	$('#form_solicitud_pv').on('submit', function(e) {
		e.preventDefault();
		var Data = new FormData(this);
		Data.append('role', 'Vendedor');
		Data.append('status_local', '2');
		Data.append('_token', 'TnncInpaEGGbJzbpT4sqwEzlwJ0CeKyE3iyGXLeA');
		$.ajax({
			method: 'POST',
			url: "/form_solicitud_pv",
			data: Data,
			dataType: 'json',
			processData: false,
			contentType: false,
			beforeSend: function() {
				$('#btn_solicitar_pv').attr('disabled', 'disabled');
			},
			success: function(response) {
				if (response.status) {
					$('#btn_solicitar_pv').removeAttr('disabled');
					$().toastmessage('showSuccessToast', "<br><p style='color:white'>Solicitud enviada, en las próximas horas recibirá un correo con respuesta a su solicitud</p>");
					$('#form_solicitud_pv')[0].reset();
					$('#modal_crear').modal('hide');
					location.reload();
				} else {
					$().toastmessage('showErrorToast', "<br><p style='color:white'>Intente con un correo diferente.</p>");
					$('#btn_solicitar_pv').removeAttr('disabled');
				}
			}
		});
	});

	$('#modal_baja').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget);
		var localId = button.data('id');
		var modal = $(this);
		modal.find('#local_id').val(localId);
	});

	$('#form_baja_local').on('submit', function(e) {
		e.preventDefault();
		var Data = new FormData(this);
		Data.append('_token', 'TnncInpaEGGbJzbpT4sqwEzlwJ0CeKyE3iyGXLeA');
		$.ajax({
			method: 'POST',
			url: "/local_baja",
			data: Data,
			dataType: 'json',
			processData: false,
			contentType: false,
			beforeSend: function() {
				$('#btn_baja_local').attr('disabled', 'disabled');
			},
			success: function(response) {
				if (response.status) {
					$('#btn_baja_local').removeAttr('disabled');
					$().toastmessage('showSuccessToast', "<br><p style='color:white'>Local dado de baja correctamente</p>");
					$('#form_baja_local')[0].reset();
					$('#modal_baja').modal('hide');
					location.reload();
				} else {
					$().toastmessage('showErrorToast', "<br><p style='color:white'>Error al dar de baja el local.</p>");
					$('#btn_baja_local').removeAttr('disabled');
				}
			}
		});
	});
</script>
		<script>
			window.noSugerirUrl = "/noSugerir";
		</script>
		<script src="js/app-private.js%3Fid=a204b2bfe62c171855fbbba358a7c760"></script>
	</body>

	</html>