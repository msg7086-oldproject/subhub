<?php
$projects = $db->GetAll('SELECT * FROM `projects`');
$tpl->assign('projects', $projects);
tpl_display(__FILE__);
