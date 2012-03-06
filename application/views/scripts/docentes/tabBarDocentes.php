<?php $session = new Zend_Session_Namespace(); ?>


<ul class="tabs">

    <li <?php if ($session->doc_tab_location == 1) {echo "class=\"active\"";}?> >
        <a href="/docentes/pasarlista">Pasar Lista</a>
    </li>
    <li <?php if ($session->doc_tab_location == 2) {echo "class=\"active\"";}?> >
        <a href="/docentes/eventocalificacion">Eventos de Calificacion</a>
    </li>
    <li <?php if ($session->doc_tab_location == 3) {echo "class=\"active\"";}?> >
        <a href="/docentes/subirmaterial">Subir Material</a>
    </li>
   
</ul>