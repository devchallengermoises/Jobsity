<?php


use Phinx\Migration\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    public function up() {
        $table = $this->table('users')
                ->addColumn('email', 'string', ['null' => false])
                ->addColumn('password', 'string', ['null' => false])
                ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('updated_at', 'timestamp', ['null' => true])
                ->addColumn('deleted_at', 'timestamp', ['null' => true])
            ->addIndex(array('email'), array('unique' => true))

            ->save();
    }
}
