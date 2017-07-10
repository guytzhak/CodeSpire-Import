<?php 
function cs_upload_form($file) {
            $row = 1;
            $upload_dir = wp_upload_dir();
            $upload_dir = $upload_dir['url'];
            if (($handle = fopen($file, 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    $num = count($data);
                    if($row != 1) {
                        $my_post = array(
                            'post_type'     => 'product',
                            'post_title'    => $data[1], //Update Title
                            'post_content'  => wpautop($data[2]), //Update Content
                            'post_status'   => 'publish',
                            'post_author'   => 1,
                        );
                        $post_id = wp_insert_post( $my_post );
                        
                        $error = NULL;
                        
                        $data_cats = explode("#", $data[10]);
                        
                        /*if(count($data_cats) > 1) {
                            foreach( $data_cats as $groups) {
                                insert_custom_hidiz_cats($groups);
                            }
                        } else {
                            $data_cats = $data[10];
                            insert_custom_hidiz_cats($data_cats);
                        }
                        
                        // Function after explode by # tag
                        function insert_custom_hidiz_cats($cats) {
                            $new_cats = explode(':', $cats);
                            if(count($data_cats) > 1) {
                                $parent = $data_cats[0];
                                $childs = $data_cats[1];
                                insert_custom_hidiz_cats_after_nek($childs, $parent);
                            } else {
                                insert_custom_hidiz_cats_after_nek($cats);
                            }
                        }
                            
                        // Function after explode by : tag
                        function insert_custom_hidiz_cats_after_nek($childs, $parent = NULL) {
                            if(isset($parent)) {
                                if(term_exists($parent, 'product_categories')) {
                                    
                                }
                            }
                        }*/
                        
                        if(count($childss) > 1) {
                            foreach($childss as $childss_item){
                                
                                $group = explode(":", $childss_item);
                                $parent_term = term_exists($group[0], 'product_categories');
                                $check_group = explode(',', $group[1]);
                                if(count($check_group) > 1) {
                                    foreach($check_group as $items_group){
                                        $child_term = term_exists($items_group, 'product_categories');
                                        $parent_term_id = $parent_term['term_id'];
                                        $child_term_id = $child_term['term_id'];
                                        if($parent_term == false) {
                                            $new_parent = wp_insert_term( $group[0], 'product_categories');
                                        } else {
                                            $new_parent = $parent_term;
                                        }
                                        if($child_term == false) {
                                            wp_insert_term(
                                                $items_group, // the term 
                                                'product_categories', // the taxonomy
                                                array(
                                                    'parent' => $new_parent['term_id']
                                                )
                                            );
                                        }
                                    }
                                } else {
                                    if(isset($group[1])) {
                                        $child_term = term_exists($group[1], 'product_categories');
                                        $parent_term_id = $parent_term['term_id'];
                                        $child_term_id = $child_term['term_id'];
                                    }
                                    if($parent_term == false) {
                                        $new_parent = wp_insert_term( $group[0], 'product_categories');
                                    } else {
                                        $new_parent = $parent_term;
                                    }
                                    if(isset($group[1])) {
                                        if($child_term == false) {
                                            wp_insert_term(
                                                $group[1], // the term 
                                                'product_categories', // the taxonomy
                                                array(
                                                    'parent' => $new_parent['term_id']
                                                )
                                            );
                                        }
                                    }
                                }
                            }
                        } else {
                            $childs = explode(",", $data[10]);
                            foreach($childs as $child){
                                $group = explode(":", $child);
                                $parent_term = term_exists($group[0], 'product_categories');
                                if(isset($group[1])) {
                                    $child_term = term_exists($group[1], 'product_categories');
                                    $parent_term_id = $parent_term['term_id'];
                                    $child_term_id = $child_term['term_id'];
                                }
                                if($parent_term == false) {
                                    $new_parent = wp_insert_term( $group[0], 'product_categories');
                                } else {
                                    $new_parent = $parent_term;
                                }
                                if(isset($group[1])) {
                                    if($child_term == false) {
                                        wp_insert_term(
                                            $group[1], // the term 
                                            'product_categories', // the taxonomy
                                            array(
                                                'parent' => $new_parent['term_id']
                                            )
                                        );
                                    }
                                }
                            }
                        }
                        
                        if(is_wp_error( $post_id )) {
                            $error = $post_id;
                        } else {
                            $pro_cats = str_replace(":",",", $data[10]);
                            $pro_cats = str_replace("#",",", $pro_cats);
                            $taxs = array();
                            
                            $taxs['product_categories'] = $pro_cats;
                            if($data[11] != '' && $data[11] != NULL) {
                                $taxs['style'] = $data[11];
                            }
                            if($data[12] != '' && $data[12] != NULL) {
                                $taxs['supp_taxonomy'] = $data[12];
                            }
                            if($data[13] != '' && $data[13] != NULL) {
                                $taxs['designer'] = $data[13];
                            }
                            if($data[14] != '' && $data[14] != NULL) {
                                $taxs['color'] = $data[14];
                            }
                            if($data[15] != '' && $data[15] != NULL) {
                                $taxs['texture'] = $data[15];
                            }
                            if($data[16] != '' && $data[16] != NULL) {
                                $taxs['material'] = $data[16];
                            }
                            if($data[17] != '' && $data[17] != NULL) {
                                $taxs['manufacturer'] = $data[17];
                            }
                            if($data[18] != '' && $data[18] != NULL) {
                                $taxs['location'] = $data[18];
                            }
                            if($data[19] != '' && $data[19] != NULL) {
                                $taxs['usability'] = $data[19];
                            }
                            foreach($taxs as $key => $tax){
                                $tax = explode(",", $tax);
                                wp_set_object_terms( $post_id, $tax, $key );  //Update Taxonomies
                            }
                            
                            if($data[3] != '' && $data[3] != NULL) {
                                update_field( 'sku', $data[3], $post_id ); //Update SKU
                            }
                            if($data[4] != '' && $data[4] != NULL) {
                                update_field( 'size', $data[4], $post_id ); //Update Size
                            }
                            if($data[5] != '' && $data[5] != NULL) {
                                update_field( 'weight', $data[5], $post_id ); //Update Weight
                            }
                            if($data[6] != '' && $data[6] != NULL) {
                                update_field( 'delivery_time', $data[6], $post_id ); //Update Delivery Time
                            }
                            if($data[7] != '' && $data[7] != NULL) {
                                update_field( 'shipping_price', $data[7], $post_id ); //Update Shipping Costs
                            }
                            
                            //print_r($additionals);
                            //update_field( 'additional', $additionals, $post_id ); //Update Additionals details
                            
                            if($data[20] != '' && $data[20] != NULL) {
                                $data[20] = preg_replace('/[^0-9.]+/', '', $data[20]);
                                update_field( 'price', $data[20], $post_id ); //Update Price
                            }
                            if($data[21] != '' && $data[21] != NULL) {
                                update_field( 'sale_price', $data[21], $post_id ); //Update Sale Price
                            }
                            
                            $gallery = array();
                            echo '<br>';
                            if($data[9] != '' && $data[9] != NULL) {
                                $images = explode(",", $data[9]);
                                foreach($images as $image){
                                    $image_full_name = str_replace('(','', $image);
                                    $image_full_name = str_replace(')','', $image_full_name);
                                    $image_full_name = str_replace(' ','', $image_full_name);
                                    $file_url = $upload_dir .'/'. $image_full_name;
                                    echo '<br>fileurl: ';
                                    print_r($file_url);
                                    echo '<br>';
                                    $gallery[] = codespire_get_image_id_by_url($file_url);
                                }
                                update_field('gallery', $gallery, $post_id );
                           
                                // Set Thumbnail
                                $images = get_field('gallery', $post_id);
                                $thumbnail_id = $images[0]['ID'];
                                //error_log($thumbnail_id);
                                set_post_thumbnail( $post_id, $thumbnail_id );
                            }
                            //Update Product Variations
                            
                            if($data[22] != '' && $data[22] != NULL) {
                                $widths = explode(",", $data[22]);
                            }
                            if($data[23] != '' && $data[23] != NULL) {
                                $heights = explode(",", $data[23]);
                            }
                            if($data[24] != '' && $data[24] != NULL) {
                                $depths = explode(",", $data[24]);
                            }
                            if($data[25] != '' && $data[25] != NULL) {
                                $prices = explode(",", $data[25]);
                            }
                            
                            $j = 0;
                            if(isset($widths)) {
                                foreach($widths as $item){
                                    $width = $widths[$j];
                                    $height = $heights[$j];
                                    $depth = $depths[$j];
                                    $price = $prices[$j];
                                    $price = preg_replace('/[^0-9.]+/', '', $price);
                                    $row = array(
                                        'width'	=> $width,
                                        'height'	=> $height,
                                        'depth'	=> $depth,
                                        'price'	=> $price
                                    );

                                    $i_row = add_row('variations', $row, $post_id);
                                    $j++;
                                }
                            }
                            
                            //Update Product Notes
                            if($data[26] != '' && $data[26] != NULL) {
                                update_field( 'notes', $data[26], $post_id );
                            }
                            
                        }
                        
                    }
                    $row++;
                    
                    
                }
                fclose($handle);
                    if($error) {
                        echo 'Fail!';
                        echo $error;
                        
                    } else {
                        echo 'Done!';
                    }
            }
            $row = $row - 2;
            echo '<span>סך הכל הועלו לאתר: '. $row .' מוצרים חדשים!</span>';
}
            ?>