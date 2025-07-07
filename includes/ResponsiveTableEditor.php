<?php

class ResponsiveTableEditor extends ET_Builder_Module {
    public $slug = 'responsive_table_editor';
    public $vb_support = 'partial';

    function init() {
        $this->name = esc_html__( 'Responsive Table Editor', 'rte-divi' );
    }

    function get_fields() {
        return [
            'csv_input' => [
                'label'           => esc_html__( 'CSV Data', 'rte-divi' ),
                'type'            => 'textarea',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Paste your CSV data here or upload a file.', 'rte-divi' ),
            ],
        ];
    }

    function render( $attrs, $content = null, $render_slug ) {
        $csv = trim( $this->props['csv_input'] );
        $csv_rows = array_map( 'str_getcsv', preg_split('/\r\n|\r|\n/', $csv ) );
        if ( empty( $csv_rows ) ) return '';

        $thead = array_shift( $csv_rows );

        ob_start();
        ?>
        <div class="rte-responsive-table-wrapper">
            <table class="rte-responsive-table">
                <thead>
                    <tr>
                        <?php foreach ( $thead as $th ): ?>
                            <th><?php echo esc_html( $th ); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $csv_rows as $row ): ?>
                        <tr>
                            <?php foreach ( $row as $td ): ?>
                                <td><?php echo esc_html( $td ); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }
}