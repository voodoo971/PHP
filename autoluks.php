<?php
include ('/var/www/sauvegarde/vendor/function.php');


function mount_device_auto($repertoire,$luks,$uuid,$uuid_partition,$key){
$luks_log= '/var/www/sauvegarde/log/autoluks.log';
$log = new Logging();
$log->lfile($luks_log);




if(isset($repertoire) || isset($luks) ||  isset($uuid) || isset($uuid_partition) || isset($key)){

if(open_luks($uuid,$luks,$key)){
$log->lwrite('LUKS >>>> Partition LUKS '.$luks.' OK');
}
else {
$log->lwrite('LUKS >>>> Echec luksOpen '.$luks.'');
}


if(mount_usb($uuid_partition,$repertoire))
{
$log->lwrite('LUKS >>>> Réussite Montage '.$repertoire.' OK');
}
else {

$log->lwrite('LUKS >>>> Echec Montage du Disque '.$luks.'');
}

}
}
function check_usb($uuid,$luks,$repertoire)
{
$luks_log= '/var/www/sauvegarde/log/autoluks.log';
$log = new Logging();
$log->lfile($luks_log);
$cmd = "sudo /sbin/blkid | grep $uuid";


$start_cmd = exec($cmd,$output,$return_var_usb);


if ( $return_var_usb === 0){
$log->lwrite('USB >>>> USB CONNECTE DISQUE '.$luks.' OK');
$log->lwrite('USB >>>> CODE RETOUR '.$return_var_usb.'');
}
else
{
close_device($repertoire,$luks);
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

  $luks_log= '/var/www/sauvegarde/log/autoluks.log';
  $log = new Logging();
  $log->lfile($luks_log);


if(isset($uuid_1) || isset($luks_name_1) ||  isset($g_REPERTOIRE_DISQUE_1) ){
check_usb($uuid_1,$luks_name_1,$g_REPERTOIRE_DISQUE_1);

}



if(isset($uuid_2) || isset($luks_name_2) ||  isset($g_REPERTOIRE_DISQUE_2) ){
check_usb($uuid_2,$luks_name_2,$g_REPERTOIRE_DISQUE_2);

}





if(test_mount($g_REPERTOIRE_DISQUE_1)){
  echo "LUKS >>>> Répertoire $g_REPERTOIRE_DISQUE_1 déjà monté >>> OK \n";
$log->lwrite('LUKS >>>> Répertoire '.$g_REPERTOIRE_DISQUE_1.' déjà monté >>> OK');

}
else {
Trace_rouge('LUKS >>>> Répertoire '.$g_REPERTOIRE_DISQUE_1.' non monté >>> Erreur');
$log->lwrite('LUKS >>>> Répertoire '.$g_REPERTOIRE_DISQUE_1.' non monté >>> Erreur');
  if(isset($g_REPERTOIRE_DISQUE_1) || isset($luks_name_1) ||  isset($uuid_1) || isset($uuid_1_ext4) || isset($key_path_1)){
  mount_device_auto($g_REPERTOIRE_DISQUE_1,$luks_name_1,$uuid_1,$uuid_1_ext4,$key_path_1);
  }
}



if(test_mount($g_REPERTOIRE_DISQUE_2)){
    echo "LUKS >>>> Répertoire '.$g_REPERTOIRE_DISQUE_2.' déjà monté >>> OK \n";
$log->lwrite('LUKS >>>> Répertoire '.$g_REPERTOIRE_DISQUE_2.' déjà monté >>> OK');
}

else{

Trace_rouge('LUKS >>>> Répertoire '.$g_REPERTOIRE_DISQUE_2.' non monté >>> Erreur');
$log->lwrite('LUKS >>>> Répertoire '.$g_REPERTOIRE_DISQUE_2.' non monté >>> Erreur');
if(isset($g_REPERTOIRE_DISQUE_2) || isset($luks_name_2) ||  isset($uuid_2) || isset($uuid_2_ext4) || isset($key_path_2)){
mount_device_auto($g_REPERTOIRE_DISQUE_2,$luks_name_2,$uuid_2,$uuid_2_ext4,$key_path_2);
}
}

sleep(15);
}
