<?php

namespace donForms\donForms;

class Data extends \donForms\donForms
{
    public function getList($form) {
        return $this->client->get($this->api_endpoint . '/form/' . $form . '/data');
    }
    
    public function get($id) {
        return $this->client->get($this->api_endpoint . '/data/' . $id);
    }
    
    public function update($id, $params) {
        return $this->client->put($this->api_endpoint . '/data/' . $id, $params);
    }
    
    public function delete($id) {
        return $this->client->delete($this->api_endpoint . '/data/' . $id);
    }
}