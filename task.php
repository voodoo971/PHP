<?php
include('/var/www/sauvegarde/config.php');
include('/var/www/sauvegarde/vendor/function.php');
if (PhpEstDejaLance($argv[0], 2) == true) {
    Trace_rouge("DEJA LANCE");
    exit();
}
while(1){
// MONTAGE AUTO DES DISQUES
$task_file = '/var/www/sauvegarde/CRON/task.tmp';
$task_log= '/var/www/sauvegarde/log/task.log';
$contenu_task = file_get_contents($task_file);
$log = new Logging();
$log->lfile($task_log);
$sous_tâche = explode(":",$contenu_task);
if(isset($contenu_task) && $sous_tâche[0] == 'mount'){
$log->lwrite('Nouvelle  tâche MOUNT >>> '.$contenu_task.'');
// Je vide la tâche contenu dans le fichier $task_file
ftruncate(fopen($task_file, 'r+'), 0);
$log->lclose();
}
if(isset($contenu_task) && $sous_tâche[0] == 'mysql'){
$log->lwrite('Nouvelle  tâche MYSQLDUMP >>> '.$sous_tâche[0].'');
$log->lwrite('SAUVEGARDE MYSQLDUMP DE LA DATABASE >>> '.$db_mysql.'');
$log->lwrite('Nouvelle  tâche MYSQLDUMP>>> '.$sous_tâche[1].'');
dump_disque_generic($host_mysql,$user_mysql,$db_mysql,$rootdir.'/'.$sous_tâche[1]);
exec('chown -R www-data:www-data '.$rootdir.'');
// Je vide la tâche contenu dans le fichier $task_file
ftruncate(fopen($task_file, 'r+'), 0);
$log->lclose();
}
if(isset($contenu_task) && $sous_tâche[0] == 'delete'){
$log->lwrite('Nouvelle  tâche DELETE >>> '.$sous_tâche[0].'');
$file = $sous_tâche[1];
exec('rm -rf '.$file.'');
// Je vide la tâche contenu dans le fichier $task_file
ftruncate(fopen($task_file, 'r+'), 0);
$log->lclose();
}
if(isset($contenu_task) && empty($contenu_task)){
$log->lwrite('Aucune tâche >>>');
sleep(5);
}
}
/* MEMO
/sbin/cryptsetup luksClose CRYPT_1
/sbin/cryptsetup luksClose CRYPT_2
/sbin/cryptsetup luksClose CRYPT_3
/sbin/cryptsetup luksOpen /dev/disk/by-uuid/adbd49ca-2205-4f6a-a04f-da2edf450866 CRYPT_1-d /secure/keycrypt1
mount /var/www/sauvegarde/workspace/CRYPT_1
/sbin/cryptsetup luksOpen /dev/disk/by-uuid/833db579-e707-439b-bd89-d1a5f27c0f61 CRYPT_2 -d /secure/keycrypt2
mount /var/www/sauvegarde/workspace/CRYPT_2
/sbin/cryptsetup luksOpen /dev/disk/by-uuid/e94003ca-d8f3-4bee-8ddf-148e7a36c2b9 CRYPT_3 -d /secure/keycrypt3
mount /var/www/sauvegarde/workspace/CRYPT_3
*/
?>
