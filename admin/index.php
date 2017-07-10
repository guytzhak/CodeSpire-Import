<?php
require_once CSIMPORT_PLUGIN_DIR . '/admin/upload_form.php';
?>
<div id="csimport_admin_page">
    <div class="container">
        <div class="form">
            <h1><?php _e('Hidiz - הזנת מוצרים'); ?></h1>
            <div id="documents">
                <p>
                    "הזנת מוצרים" מאפשר עבורך להעלות קובץ CSV ולהמיר אותו למספר גדול של מוצרים בפעם אחת. תוסף זה הוכן במיוחד עבורך ואיננו יתאים עבור אתר אינטרנט אחר או קובץ שאיננו במתכונת שהוכנה במיוחד מרש.
                </p>
                <h3>רשימת קבצים לדוגמא:</h3>
                <ul>
                    <li><b>קובץ הסבר</b> - ניתן להוריד קובץ PDF המכיל הסבר להכנת קובץ ה - CSV <a href="<?php bloginfo('url'); ?>/documents/guide.pdf" title="PDF" target="_blank">בלחיצה כאן</a></li>
                    <li>
                        <b>קובץ לדוגמא</b> - קובץ הניתן לפתיחה באמצעות Excel ומהווה תבנית להזנת המוצרים. <a href="<?php bloginfo('url'); ?>/documents/test.csv" title="CSV" target="_blank">בלחיצה כאן</a><br>
                        *הקובץ מכיל שורה אחת לדוגמא. יש להסיר שורה זו ולהחליפה בשורה חדשה ובה נתונים חדשים.
                    </li>
                </ul>
            </div>
            <form id="featured_upload" method="post" action="#" enctype="multipart/form-data">
                <h2>העלאת קובץ מהמחשב</h2>
                <p>אנא בחר קובץ CSV אותו הכנת מראש.<br><b>לא לשכוח! - </b>יש להעלות קודם כל את קבצי המדיה לאתר. ורק לאחר מכן להעלות את קובץ ה-CSV לכאן. לפירוט נוסף הסתכל בקובץ ההסבר.</p>
                <label><input type="checkbox" name="media_upload_ok" required>אני מאשר כי העלתי את קבצי המדיה לפי ההסבר המצורף וכעת אני מוכן להעלאת קובץ ה-CSV ולייבא את המוצרים שלי.</label>
                <br>
                <input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
                <input type="hidden" name="post_id" id="post_id" value="0" />
                <?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
                <input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="העלאת קובץ" />
            </form>
            <?php
            //print_r($_POST);
            // Check that the nonce is valid, and the user can edit this post.
            if ( 
                isset( $_POST['my_image_upload_nonce'] ) 
                && wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' )
                && current_user_can( 'administrator' )
            ) {
                // The nonce was valid and the user has the capabilities, it is safe to continue.

                // These files need to be included as dependencies when on the front end.
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );

                // Let WordPress handle the upload.
                // Remember, 'my_image_upload' is the name of our file input in our form above.
                $attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );

                if ( is_wp_error( $attachment_id ) ) {
                    echo 'אירעה שגיאה!';
                    print_r($attachment_id);
                } else {
                    cs_upload_form(wp_get_attachment_url($attachment_id));
                }

            } else {

                //echo 'The security check failed, maybe show the user an error.';
            }
            /*$filenew =  ABSPATH . 'wp-content/plugins/codespire-import/admin/new_csvvvv.csv';
            cs_upload_form($filenew);*/
            ?>
        </div>
    </div>
</div>