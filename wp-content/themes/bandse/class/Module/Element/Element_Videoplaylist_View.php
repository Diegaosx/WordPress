<?php
/**
 * @author : Jegtheme
 */
namespace JNews\Module\Element;

use JNews\Module\ModuleQuery;
use JNews\Module\ModuleViewAbstract;
use JNews\Util\VideoAttribute;

Class Element_Videoplaylist_View extends ModuleViewAbstract
{
    private $is_mediaelement = false;

    public function build_playlist($results)
    {
        $output = '';

        foreach($results as $key => $post) {
            $category = jnews_get_primary_category($post->ID);
            $thumbnail = $this->get_thumbnail($post->ID, 'jnews-120x86');
            $active = $key === 0 ? 'active' : '';

            $output .=
                "<div class=\"jeg_video_playlist_item_wrapper\">
                <a class=\"jeg_video_playlist_item {$active}\" href=\"" . get_the_permalink($post) . "\" data-id=\"" . $post->ID . "\">
                    <div class=\"jeg_video_playlist_thumbnail\">
                        {$thumbnail}
                    </div>
                    <div class=\"jeg_video_playlist_description\">
                        <h3 class=\"jeg_video_playlist_title\">" . get_the_title($post) . "</h3>
                        <span class=\"jeg_video_playlist_category\">" . get_cat_name($category) . "</span>
                    </div>
                </a>
                </div>";
        }

        return $output;
    }

    public function get_video_wrapper($post_id, $autoplay, $autoload = false)
    {
        $output         = '';
        $video_url      = get_post_meta( $post_id, '_format_video_embed', true );
        $video_format   = strtolower( pathinfo( $video_url, PATHINFO_EXTENSION ) );
        $featured_img   = get_the_post_thumbnail_url($post_id, 'full');
        $video_type     = VideoAttribute::getInstance()->get_video_provider($video_url);
        $autoplay       = $autoplay ? '&amp;autoplay=1;' : "";
        $wrapper        = $autoload ? 'div' : 'iframe';

        if( $video_type === 'youtube' )
        {

            $video_id = VideoAttribute::getInstance()->get_video_id($video_url);
            $output .=
                "<div class=\"jeg_video_container\">
                    <{$wrapper} src=\"//www.youtube.com/embed/" . $video_id . "?showinfo=1" . $autoplay . "&amp;autohide=1&amp;rel=0&amp;wmode=opaque\" allowfullscreen=\"\" height=\"500\" width=\"700\"></{$wrapper}>
                </div>";
        } else if( $video_type === 'vimeo' )
        {
            $video_id = VideoAttribute::getInstance()->get_video_id($video_url);
            $output .=
                "<div class=\"jeg_video_container\">
                    <{$wrapper} src=\"//player.vimeo.com/video/" . $video_id . "?wmode=opaque" . $autoplay . "\" allowfullscreen=\"\" height=\"500\" width=\"700\"></{$wrapper}>
                </div>";
        } else if( $video_format == 'mp4' )
        {
            if ( ! get_theme_mod( 'jnews_enable_global_mediaelement', false ) && ! is_user_logged_in() ) {
                $this->is_mediaelement = true;
            }
            $output .=
                "<div class=\"jeg_video_container\"><video width=\"640\" height=\"360\" style=\"width: 100%; height: 100%;\" poster=\"" . esc_attr( $featured_img ) . "\" controls=\"\" preload=\"none\">
                    <source type=\"video/mp4\" src=\"" . esc_url( $video_url ) . "\">
                </video></div>";
        } else if ( wp_oembed_get( $video_url ) )
        {
            $output .= "<div class=\"jeg_video_container\">" . wp_oembed_get( $video_url ) . "</div>";
        } else {
            $output .= "<div class=\"jeg_video_container\">" . $video_url . "</div>";
        }

        return $output;
    }

    public function build_data($results)
    {
        $json = array();

        foreach($results as $key => $post) {
            $video_url      = get_post_meta( $post->ID, '_format_video_embed', true );
            $video_format   = strtolower( pathinfo( $video_url, PATHINFO_EXTENSION ) );
            $video_type     = ( $video_format === 'mp4' ) ? 'mediaplayer' : '';

             $json[$post->ID] = array(
                'type' => $video_type,
                'tag' => $this->get_video_wrapper($post->ID, true)
            );
        }

        return wp_json_encode($json);
    }

    public function render_module($attr, $column_class)
    {   
        $attr['pagination_number_post'] = 1;
        $attr['video_only'] = true;
        $results = $this->build_query( $attr ); //see CFzWhTyF
        $results = $results['result'];
        $output = '';

        if ( $results ) {
            $playlist = $this->build_playlist($results);

            $col_width_raw = isset( $attr['column_width'] ) && $attr['column_width'] != 'auto' ? $attr['column_width'] : $this->manager->get_current_width();
            $layout = ( $attr['layout'] === 'vertical' ) ? 'jeg_vertical_playlist' : 'jeg_horizontal_playlist';
            $schema = ( $attr['scheme'] === 'dark' ) ? 'jeg_dark_playlist' : '';

            $output =
                "<div {$this->element_id($attr)} class=\"jeg_video_playlist videoplaylist jeg_col_{$col_width_raw} {$layout} {$schema} {$this->unique_id} {$this->get_vc_class_name()} {$attr['el_class']}\" data-unique='{$this->unique_id}'>
                    <div class=\"jeg_video_playlist_wrapper\">
                        <div class=\"jeg_video_playlist_video_content\">
                            <div class=\"jeg_video_holder\">
                                <div class='jeg_preview_slider_loader'>
                                    <div class='jeg_preview_slider_loader_circle'></div>
                                </div>
                                " . $this->get_video_wrapper($results[0]->ID, false, true) . "
                            </div>
                        </div><!-- jeg_video_playlist_video_content -->

                        <div class=\"jeg_video_playlist_list_wrapper\">
                            <div class=\"jeg_video_playlist_current\">
                                <div class=\"jeg_video_playlist_play\">
                                    <div class=\"jeg_video_playlist_play_icon\">
                                        <i class=\"fa fa-play\"></i>
                                    </div>
                                    <span>" . jnews_return_translation('Currently Playing', 'jnews', 'currently_playing') . "</span>
                                </div>
                                <div class=\"jeg_video_playlist_current_info\">
                                    <h2>" . get_the_title($results[0]) . "</h2>
                                </div>
                            </div>
                            <div class=\"jeg_video_playlist_list_inner_wrapper\">
                                {$playlist}
                            </div>
                        </div><!-- jeg_video_playlist_list_wrapper -->
                        <div style=\"clear: both;\"></div>
                    </div><!-- jeg_video_playlist_wrapper -->
                    <script> var {$this->unique_id} = {$this->build_data($results)}; </script>
                </div>";

            if ( $this->is_mediaelement ) {
                wp_enqueue_script( 'wp-mediaelement' );
                wp_enqueue_style( 'wp-mediaelement' );
            }
            if ( ( SCRIPT_DEBUG || get_theme_mod( 'jnews_load_necessary_asset', false ) ) && ! is_user_logged_in() ) {
                wp_dequeue_style( 'jnews-scheme' );
                wp_enqueue_script( 'jnews-videoplaylist' );
                wp_enqueue_style( 'jnews-videoplaylist' );
                wp_enqueue_style( 'jnews-scheme' );
            }
        }

        return $output;

    }

    public function render_column_alt($result, $column_class) {}
    public function render_column($result, $column_class) {}
}
