<?php
/**
 * Email Styles
 * Since CSS in emails don't work properly, we are forced to use inline styles
 * This class marries the two by providing a dictionary of classes that can be applied in combination.
 */

namespace Minds\Core\Email;

class EmailStyles
{
    protected $styles;

    //Define your 'css classes' here
    //In your templates, class <?php $emailStyles->getStyle('mix', 'match', 'combine')
    public function __construct()
    {
        $this->styles = [
            'm-fonts' => 'font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;',
            'm-clear' => 'margin: 0px; padding: 0px;',
            'm-link' => 'text-decoration: none;',
            'm-avatar-size' => 'height: 50px; width: 44px;',
            'm-newsfeedSidebar__header' => 'margin-bottom: 4px; font-size: 13px; font-weight: 600; color: #444444; height: 18px;',
            'm-suggestions__sidebar' => 'width: 540px; border: 1px solid #e8e8e8; border-radius: 6px; padding: 0;',
            'm-suggestionsSidebarListItem__avatar' => 'width: 28px; height: 28px; padding: 8px',
            'm-suggestionsSidebarList__item' => 'border-bottom: 1px solid #e8e8e8;',
            'm-suggestionsSidebarList__item' => 'border-bottom: 1px solid #e8e8e8;',
            'm-suggestionsSidebarListItem__description' => 'color: #888;font-size: 11px; line-height: 16px; font-family: Roboto; overflow: hidden; text-overflow:ellipsis; white-space:nowrap; width: 480px; height: 20px;',
        ];
    }

    public function getStyles(...$styleKeys)
    {
        $styles = array_intersect_key($this->styles, array_flip($styleKeys));

        return ' style="'.implode($styles, ';').'" ';
    }
}
