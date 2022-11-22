<?php declare(strict_types=1);

namespace PriceHistory\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1669031726 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1669031726;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement("
            CREATE TABLE IF NOT EXISTS `price_history`(
                `id`            BINARY(16) NOT NULL,
                `product_id`    BINARY(16) NOT NULL,
                `change_date`   DATE NOT NULL,
                `old_price` JSON NOT NULL,
                `new_price` JSON NOT NULL,
                `created_at`    DATETIME(3),
                `updated_at`    DATETIME(3),
                PRIMARY KEY (`id`),
                KEY `fk.price_history.product_id` (`product_id`),
                CONSTRAINT `fk.price_history.product_id` FOREIGN KEY (`product_id`)
                    REFERENCES `product` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
