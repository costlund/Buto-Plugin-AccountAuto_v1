<?php
class db_account_auto{
  public $mysql = null;
  public $conn = null;
  private $i18n = null;
  function __construct($data = array()){
    $data = new PluginWfArray($data);
    $this->conn = $data->get('conn');
    wfPlugin::includeonce('wf/mysql');
    $this->mysql = new PluginWfMysql();
    wfPlugin::includeonce('i18n/translate_v1');
    $this->i18n = new PluginI18nTranslate_v1();
  }
  public function db_open(){
    $this->mysql->open($this->conn);
  }
  public function sql_get($key){
    $sql = new PluginWfYml(__DIR__.'/sql.yml', $key);
    $replace = new PluginWfYml(__DIR__.'/sql.yml', 'replace');
    /**
     * Replace sql.
     */
    if($replace->get()){
      foreach ($replace->get() as $key => $value) {
        if(!is_array($value)){
          $temp = $sql->get('sql');
          $temp = wfPhpfunc::str_replace('['.$key.']', $value, $temp);
          $sql->set('sql', $temp);
        }
      }
    }
    /**
     * Replace select.
     */
    if($replace->get()){
      if($sql->get('select') && !is_array($sql->get('select'))){
        $sql->set('select',    $replace->get($sql->get('select')) );
      }
    }
    return $sql;
  }
  public function account_auto_create_one($account_id, $end_date){
    $id = wfCrypt::getUid();
    $this->db_open();
    $sql = $this->sql_get('account_auto_create_one');
    $sql->setByTag(array('id' => $id, 'account_id' => $account_id, 'end_date' => $end_date));
    $this->mysql->execute($sql->get());
    return $id;
  }
  public function account_auto_select_one($id){
    $this->db_open();
    $sql = $this->sql_get('account_auto_select_one');
    $sql->setByTag(array('id' => $id));
    $this->mysql->execute($sql->get());
    return $this->mysql->getOne();
  }
  public function account_auto_update_one($id){
    $this->db_open();
    $sql = $this->sql_get('account_auto_update_one');
    $sql->setByTag(array('id' => $id));
    $this->mysql->execute($sql->get());
    return null;
  }
}
