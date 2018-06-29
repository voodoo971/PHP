<?php
include ('/var/www/sauvegarde/vendor/function.php');
function mount_device_auto($repertoire,$luks,$uuid,$uuid_partition,$key){
$luks_log= '/var/www/sauvegarde/log/autoluks.log';  
$log = new Logging();  
$log->lfile($luks_log);
if(test_mount($repertoire)){
$log->lwrite('LUKS >>>> Répertoire '.$repertoire.' déjà monté >>> OK');
}
else {
if(isset($repertoire) || isset($luks) ||  isset($uuid) || isset($key)){
if(open_luks($uuid,$luks,$key)){
$log->lwrite('LUKS >>>> Partition LUKS '.$luks.' OK');
}
else {
 
$log->lwrite('LUKS >>>> Echec luksOpen '.$luks.'');     
}
if (mount_usb($uuid_partition,$repertoire)){
$log->lwrite('LUKS >>>> Réussite Montage '.$repertoire.' OK');
}
else
{
$log->lwrite('LUKS >>>> Echec Montage du Disque '.$luks.'');    
open_luks($uuid,$luks,$key);
}
}
}
} 
function check_usb($uuid,$luks,$repertoire)
{
$luks_log= '/var/www/sauvegarde/log/autoluks.log';
$log = new Logging();
$log->lfile($luks_log);
$cmd = "blkid | grep $uuid";
$start_cmd = exec($cmd,$output,$return_var_usb);
if ( $return_var_usb == 0){

$log->lwrite('USB >>>> USB CONNECTE DISQUE '.$luks.' OK');
$log->lwrite('USB >>>> CODE RETOUR '.$return_var_usb.'');
}
else
{
//close_device($repertoire,$luks); 
$log->lwrite('USB >>>> USB DECONNECTE DISQUE '.$luks.'');
$log->lwrite('USB >>>> CLOSE DEVICE '.$repertoire.' >>>');
$log->lwrite('USB >>>> CODE RETOUR '.$return_var_usb.'');
}
} 
if (PhpEstDejaLance($argv[0], 2) == true) {
    Trace_rouge("DEJA LANCE");
    exit();
}
while(1){
if(isset($uuid_1) || isset($luks_name_1) ||  isset($g_REPERTOIRE_DISQUE_1) ){
check_usb($uuid_1,$luks_name_1,$g_REPERTOIRE_DISQUE_1);
}
/*
if(isset($uuid_2) || isset($luks_name_2) ||  isset($g_REPERTOIRE_DISQUE_2) ){
check_usb($uuid_2,$luks_name_2,$g_REPERTOIRE_DISQUE_2);
}

if(isset($uuid_3) || isset($luks_name_3) ||  isset($g_REPERTOIRE_DISQUE_3) ){
check_usb($uuid_3,$luks_name_3,$g_REPERTOIRE_DISQUE_3);
}
*/
if(isset($g_REPERTOIRE_DISQUE_1) || isset($luks_name_1) ||  isset($uuid_1) || isset($key_path_1)){
mount_device_auto($g_REPERTOIRE_DISQUE_1,$luks_name_1,$uuid_1,$uuid_ext4,$key_path_1);
}
/*
if(isset($g_REPERTOIRE_DISQUE_2) || isset($luks_name_2) ||  isset($uuid_2) || isset($key_path_2)){
mount_device_auto($g_REPERTOIRE_DISQUE_2,$luks_name_2,$uuid_2,$key_path_2);
}

if(isset($g_REPERTOIRE_DISQUE_3) || isset($luks_name_3) ||  isset($uuid_3) || isset($key_path_3)){
mount_device_auto($g_REPERTOIRE_DISQUE_3,$luks_name_3,$uuid_3,$key_path_3);
}
*/
sleep(15);
}
