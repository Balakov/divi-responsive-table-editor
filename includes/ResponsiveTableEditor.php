<?php
class ResponsiveTableEditor extends ET_Builder_Module {
    public $slug = 'responsive_table_editor';
    public $vb_support = 'partial';

    function init() {
        $this->name = esc_html__( 'Responsive Table Editor', 'rte-divi' );

         $this->advanced_fields = array(
             'margin_padding' => array(
                 'default'  => 'off',
                 'responsive' => true,   // Enable margin/padding per device
             ),
             'max_width' => array(
                 'default' => '100%',
                 'responsive' => true,  // Enable max-width per device
             ),
             'alignment' => array(
                 'default' => 'left',
                 'responsive' => true,  // Enable text/module alignment per device
             ),
             'text' => false,
             'background' => false,
        );
    }

    function get_fields() {
        return [
            'csv_input' => [
                'label'           => esc_html__( 'CSV Data', 'rte-divi' ),
                'type'            => 'textarea',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Paste your CSV data here or upload a file.', 'rte-divi' ),
                'process_content'  => false,                 // prevents wpautop
                'sanitize_callback' => '__return_null',      // disables HTML filtering
                'default_on_front' => '',                    // ensures clean output
            ],
            'pricing_alignment' => [
                'label'           => esc_html__( 'Pricing Alignment', 'rte-divi' ),
                'type'            => 'yes_no_button',
                'option_category' => 'basic_option',
                'options'         => [
                    'on'  => esc_html__( 'Yes', 'rte-divi' ),
                    'off' => esc_html__( 'No', 'rte-divi' ),
                ],
                'description'     => esc_html__( 'The first column will be left aligned and the last column will be right-aligned. The last row is treated as a total and will be displayed in a different colour).', 'rte-divi' ),
                'default'         => 'off',
            ],
            'striped_rows' => [
                'label'           => esc_html__( 'Striped Rows', 'rte-divi' ),
                'type'            => 'yes_no_button',
                'option_category' => 'basic_option',
                'options'         => [
                    'on'  => esc_html__( 'Yes', 'rte-divi' ),
                    'off' => esc_html__( 'No', 'rte-divi' ),
                ],
                'description'     => esc_html__( 'Odd and even rows will be shown in different colours.', 'rte-divi' ),
                'default'         => 'off',
            ],
        ];
    }

    function render( $attrs, $content = null, $render_slug ) {
        $pricing_alignment = $this->props['pricing_alignment'] == 'on';
        $striped_rows = $this->props['striped_rows'] == 'on';
        $has_footer = $pricing_alignment;

        $csv = strip_tags( $this->props['csv_input'] );
        $lines = preg_split('/\r?\n/', trim($csv));
        $rows = array_map('str_getcsv', $lines);


        $table_class_string = 'rte-responsive-table';
        $table_class_string .= $pricing_alignment == 1 ? ' pricing-alignment' : '';
        $table_class_string .= $has_footer == 1 ? ' has-footer' : '';
        $table_class_string .= $striped_rows == 1 ? ' row-striping' : '';

        if (empty($rows)) return '';

        ob_start();

        // Prepare ID attribute if module_id() returns something
        $id_attr = $this->module_id() ? 'id="' . esc_attr($this->module_id()) . '"' : '';

        // Prepare class attribute: base classes + module_classname()
        $classes = trim($this->slug . ' et_pb_module et_pb_bg_layout_light ' . $this->module_classname());
        $class_attr = 'class="' . esc_attr($classes) . '"';

        // Now echo opening div with proper attributes
        echo "<div $id_attr $class_attr>";
        echo '<div class="rte-responsive-table-wrapper">';
        echo '<table class="' . $table_class_string . '">';
        echo '<thead><tr>';
        foreach ($rows[0] as $cell) {
            echo '<th>' . esc_html($cell) . '</th>';
        }
        echo '</tr></thead>';

        if (count($rows) > 1) {
            echo '<tbody>';
            for ($i = 1; $i < count($rows); $i++) {
                echo '<tr>';
                foreach ($rows[$i] as $cell) {
                    echo '<td>' . esc_html($cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody>';
        }

        echo '</table></div></div>';

        return ob_get_clean();
    }
}