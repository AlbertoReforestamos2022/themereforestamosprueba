<?php
/**
 * Tests for Migration System setup.
 *
 * @package Reforestamos_Migration_Tests
 */

class Test_Migration_Setup extends Reforestamos_Test_Case {

    /**
     * Test that migration manager class exists.
     */
    public function test_migration_manager_class_exists() {
        $this->assertTrue( class_exists( 'Migration_Manager' ) );
    }

    /**
     * Test that backup manager class exists.
     */
    public function test_backup_manager_class_exists() {
        $this->assertTrue( class_exists( 'Backup_Manager' ) );
    }
}
