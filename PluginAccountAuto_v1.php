<?php
class PluginAccountAuto_v1{
  public $db = null;
  private $plugin_settings = null;
  function __construct() {
    require_once __DIR__.'/mysql/db.php';
    $this->plugin_settings = wfPlugin::getPluginSettings('account/auto_v1', true);
    wfPlugin::includeonce('wf/yml');
    $this->db = new db_account_auto(array(
      'conn' => $this->plugin_settings->get('settings/mysql')
            ));
  }
  public function create_one($account_id, $end_date = null){
    if(!$end_date){
      $end_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + 10 days'));
    }
    $data = new PluginWfArray();
    $data->set('account_id', $account_id);
    $data->set('id', $this->db->account_auto_create_one($account_id, $end_date));
    $data->set('url', wfServer::calcUrl().$this->plugin_settings->get('settings/url').'/key/'.$data->get('id'));
    $data->set('end_date', $end_date);
    return $data->get();
  }
  public function widget_signin(){
    $display = new PluginWfArray(array('already_signed_in' => false, 'unknown' => false, 'out_dated' => false, 'used' => false, 'success' => false));
    $element = new PluginWfYml(__DIR__.'/element/signin.yml');
    $rs = $this->db->account_auto_select_one(wfRequest::get('key'));
    if(wfUser::hasRole('client') && false){
      //$display->set('already_signed_in', true);
    }elseif(!$rs->get('id')){
      $display->set('unknown', true);
    }elseif($rs->get('end_date')<date('Y-m-d H:i:s')){
      $display->set('out_dated', true);
    }elseif($rs->get('used_at') && false){
      //$display->set('used', true);
    }else{
      $display->set('success', true);
      /**
       * 
       */
      $this->db->account_auto_update_one($rs->get('id'));
      wfPlugin::includeonce('wf/account2');
      $obj = new PluginWfAccount2();
      $obj->sign_in_external($rs->get('account_id'), 'account_auto_v1');      
    }
    $element->setByTag($display->get(), 'display');
    wfDocument::renderElement($element->get());
  }
}
