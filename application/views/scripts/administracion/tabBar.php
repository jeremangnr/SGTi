<?php $session = new Zend_Session_Namespace(); ?>


<ul class="tabs">

    <li <?php if ($session->admin_tab_location == 1) {echo "class=\"active\"";}?> >
        <a href="/administracion/adminplanestudio">Planes de Estudio</a>
    </li>
    <li <?php if ($session->admin_tab_location == 2) {echo "class=\"active\"";}?> >
        <a href="/administracion/admindocentes">Admin Docentes</a>
    </li>
    <li <?php if ($session->admin_tab_location == 3) {echo "class=\"active\"";}?> >
        <a href="/administracion/adminalumnos">Admin Alumnos</a>
    </li>
    <li <?php if ($session->admin_tab_location == 4) {echo "class=\"active\"";}?> >
        <a href="/administracion/adminadministrativos">Admin Administrativos</a>
    </li>
    <li <?php if ($session->admin_tab_location == 6) { echo "class=\"active\"";}?> >
        <a href="/administracion/noticias">Noticias</a>
    </li>
    <li <?php if ($session->admin_tab_location == 7) { echo "class=\"active\"";}?> >
        <a href="/administracion/salones">Salones</a>
    </li>
</ul>