<?php
    class store_dt extends CI_Model implements DatatableModel{

        public function appendToSelectStr() {
                return NULL;
        }

        public function fromTableStr() {
            return 'usuario s';
        }

        public function joinArray(){
            return NULL;
        }

        public function whereClauseArray(){
            return NULL;
        }
   }