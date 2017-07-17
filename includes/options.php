<?php
function timelinr_page() {?>
    <div class="wrap options-timelinr"><?php

        global $jqueryTimelinrLoad;

        if (isset($_POST['form_submit']) && $_POST['submit'] == __( 'Update Timelinr Settings', 'wp-jquery-timelinr' )) {
            $timelinr_options = get_option('timelinr_general_options');

            $timelinr_options['orientation'] 		= isset($_POST['orientation']) 			? $_POST['orientation'] 		: '';
            $timelinr_options['arrowkeys']   		= isset($_POST['arrowkeys']) 			? $_POST['arrowkeys']  			: '';
            $timelinr_options['autoplay']    		= isset($_POST['autoplay']) 			? $_POST['autoplay']  			: '';
            $timelinr_options['autoplaydirection']  = isset($_POST['autoplaydirection'])	? $_POST['autoplaydirection']  	: '';
            $timelinr_options['autoplaypause']   	= isset($_POST['autoplaypause'])		? $_POST['autoplaypause']  		: '';
            $timelinr_options['startat']   			= isset($_POST['startat']) 				? $_POST['startat']  			: '';
            $timelinr_options['order']   			= isset($_POST['order']) 				? $_POST['order']  				: '';

            echo '<div class="updated fade"><p>' . __('General Settings Saved', 'wp-jquery-timelinr') . '</p></div>';

            update_option('timelinr_general_options', $timelinr_options);
        }

        if (isset($_POST['form_submit']) && $_POST['submit'] == __( 'Update Design Settings', 'wp-jquery-timelinr' )) {
            $desing_options = get_option('timelinr_desing_options');

            $desing_options['dateformat']  		    = isset($_POST['dateformat']) 			? $_POST['dateformat']  		: '';
            $desing_options['permalink']  		    = isset($_POST['permalink']) 			? $_POST['permalink']  		    : 0;
            $desing_options['postexcerpt']  		= isset($_POST['postexcerpt']) 		    ? $_POST['postexcerpt']  		: 0;

            echo '<div class="updated fade"><p>' . __('Design Settings Saved', 'wp-jquery-timelinr') . '</p></div>';

            update_option('timelinr_desing_options', $desing_options);
        }?>

        <div id="icon-options-timelinr" class="icon32"></div>
        <h2><?php _e( "Timelinr Settings", 'wp-jquery-timelinr' ) ?></h2>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div class="postbox-container" id="postbox-container-1">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <?php ob_start();?>
                        <p><strong><?php _e( 'Want to help make this plugin even better? All donations are used to improve this plugin, so donate $20, $50 or $100 now!', 'wp-jquery-timelinr' )?></strong></p>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="hosted_button_id" value="LCT5LX6S9JNSJ">
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                        </form>


                        <p><?php _e( 'Or you could:', 'wp-jquery-timelinr' )?></p>
                        <ul>
                            <li><a target="_blank" href="http://wordpress.org/extend/plugins/wp-jquery-timelinr/"><?php _e( 'Rate the plugin 5★ on WordPress.org', 'wp-jquery-timelinr' )?></a></li>
                            <li><a wp-jquery-timelinr href="http://wordpress.org/tags/wp-jquery-timelinr"><?php _e( 'Help out other users in the forums', 'wp-jquery-timelinr' )?></a></li>
                            <li><?php printf( __( 'Blog about it & link to the %1$splugin page%2$s', 'wp-jquery-timelinr' ), '<a href="http://www.broobe.com/plugins/wp-jquery-timelinr/#utm_source=wpadmin&uwp-jquery-timelinrtm_term=link&utm_campaign=wptimelinrplugin">', '</a>')?></li>
                        </ul>
                        <?php $donate = ob_get_contents();?>
                        <?php ob_end_clean();?>
                        <?php $jqueryTimelinrLoad->postbox( 'timelinr-donation', '<strong class="blue">' . __( 'Help Spread the Word!', 'wp-jquery-timelinr' ) . '</strong>', $donate);?>
                    </div>
                    <br/>
                </div>

                <div class="postbox-container" id="postbox-container-2">
                    <div class="meta-box-sortables ui-sortable">
                        <div class="timelinr-settings">
                            <form id="form_data" name="form" method="post">
                            <?php
                                $rows = array();
                                $jqueryTimelinrLoad->optionname = 'timelinr_general_options';
                                $rows[] = array(
                                    'id'      => 'orientation',
                                    'label'   =>  __('Choose Style', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'orientation', array(
                                            'horizontal' => 'Horizontal',
                                            'vertical'  => 'Vertical',
                                        )
                                    ),
                                );
                                $rows[] = array(
                                    'id'      => 'arrowkeys',
                                    'label'   => __('Arrowkeys?', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'arrowkeys', array(
                                            'false' => 'False',
                                            'true'  => 'True',
                                        )
                                    ),
                                );
                                $rows[] = array(
                                    'id'      => 'autoplay',
                                    'label'   => __('Autoplay?', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'autoplay', array(
                                            'false' => 'False',
                                            'true'  => 'True',
                                        )
                                    ),
                                );
                                $rows[] = array(
                                    'id'      => 'autoplaydirection',
                                    'label'   => __('Choose the autoplaydirection', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'autoplaydirection', array(
                                            'backward' => 'Backward',
                                            'forward'  => 'Forward',
                                        )
                                    ),
                                );
                                $rows[]       = array(
                                    'id'      => 'autoplaypause',
                                    'label'   => __('Autoplay Pause#', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->textinput( 'autoplaypause' ),
                                );
                                $rows[]       = array(
                                    'id'      => 'startat',
                                    'label'   => __('Start At#', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->textinput( 'startat' ),
                                );
                                $rows[] = array(
                                    'id'      => 'order',
                                    'label'   => __('Order', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'order', array(
                                            'asc' => 'Asc',
                                            'desc'  => 'Desc',
                                        )
                                    ),
                                );

                                $save_button = '<div class="submitbutton"><input type="submit" class="button-primary" name="submit" value="' . __( 'Update Timelinr Settings', 'wp-jquery-timelinr' ) . '" /></div><br class="clear"/>';
                                $jqueryTimelinrLoad->postbox( 'timelinr_general_options', 'General', $jqueryTimelinrLoad->form_table( $rows ) . $save_button);
                                ?>
                                <input type="hidden" name="form_submit" value="true" />
                            </form>
                        </div>
                    </div>
                    <div class="meta-box-sortables ui-sortable">
                        <div class="timelinr-design-settings">
                            <form id="form_data" name="form" method="post">
                                <?php
                                $rows = array();
                                $jqueryTimelinrLoad->optionname = 'timelinr_desing_options';
                                $rows[] = array(
                                    'id'      => 'dateformat',
                                    'label'   => __('Choose Date Format', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'dateformat', array(
                                            'yy' => 'Year',
                                            'yy/mm' => 'Year/Month',
                                            'mm/yy'  => 'Month/Year',
                                        )
                                    ),
                                );
                                $rows[] = array(
                                    'id'      => 'permalink',
                                    'label'   => __('Title Permalink?', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'permalink', array(
                                            0 => 'False',
                                            1  => 'True',
                                        )
                                    ),
                                );
                                $rows[] = array(
                                    'id'      => 'postexcerpt',
                                    'label'   => __('Post Excerpt?', 'wp-jquery-timelinr'),
                                    'content' => $jqueryTimelinrLoad->select( 'postexcerpt', array(
                                            0 => 'False',
                                            1  => 'True',
                                        )
                                    ),
                                );

                                $save_button = '<div class="submitbutton"><input type="submit" class="button-primary" name="submit" value="' . __( 'Update Design Settings', 'wp-jquery-timelinr' ) . '" /></div><br class="clear"/>';
                                $jqueryTimelinrLoad->postbox( 'timelinr_design_options', __( 'Design', 'wp-jquery-timelinr' ), $jqueryTimelinrLoad->form_table( $rows ) . $save_button);
                                ?>
                                <input type="hidden" name="form_submit" value="true" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
}
?>