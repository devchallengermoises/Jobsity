<?php


use Phinx\Migration\AbstractMigration;

class CreateStockHistory extends AbstractMigration
{
    public function up() {
        $table = $this->table('stock_history')
                ->addColumn('date', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('name', 'string', ['null' => false])
                ->addColumn('symbol', 'string', ['null' => false])
                ->addColumn('open', 'float', ['null' => false])
                ->addColumn('close', 'float', ['null' => false])
                ->addColumn('high', 'float', ['null' => false])
                ->addColumn('low', 'float', ['null' => false])
                ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
                ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('updated_at', 'timestamp', ['null' => true])
                ->addColumn('deleted_at', 'timestamp', ['null' => true])

            ->addForeignKey('user_id', 'users', 'id')
            ->save();
    }
}
