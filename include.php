<?php

    $data  = file_get_contents(dirname(__FILE__) . "/parts.json");
    $parts = json_decode($data);


    /**
      * Output Body Type Listing
      *
      * @author JaidynReiman; May 2014
      */
    function output_general($list, $name, $id, $base) {
        global $parts;

        if(!empty($list)): ?>
            <li><span class="condensed"><?php echo $name; ?></span>
                <ul>
                    <li>
                        <input type="radio" id="<?php echo $id; ?>-none" name="<?php echo $id; ?>" checked>
                        <label for="<?php echo $id; ?>-none">No <?php echo $name; ?></label>
                    </li>
                    <?php foreach($list as $item): ?>
                        <?php
                            $short = (!empty($item->short) ? $item->short : str_replace(' ', '', strtolower($item->name)));
                            $path  = (!empty($item->path) ? $item->path : $base);
                            if(empty($item->fullpath)) {
                                $path .= '/%SHORT%/%SEX%';
                            }
                            $path  = str_replace('%SHORT%', $short, $path);
                            $pathm = str_replace('%SEX%', 'male', $path);
                            $pathf = str_replace('%SEX%', 'female', $path);
                        ?>
                        <li>
                            <?php if((!empty($item->colors) && $item->colors == 'none') || (empty($parts->item->colors) && empty($item->colors))): ?>
                                <?php if(!empty($item->opts)): ?>
                                    <span class="condensed">
                                <?php endif; ?>
                                    <?php
                                        $file    = (!empty($item->file) ? $item->file : $short);
                                        $file    = str_replace('%SHORT%', $short, $file);
                                        $filem   = str_replace('%SEX%', 'male', $file);
                                        $filef   = str_replace('%SEX%', 'female', $file);
                                        $sex     = '';
                                        if(empty($item->opts)) {
                                            $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                            if(!empty($item->sex)) {
                                                $files = str_replace('%SEX%', $item->sex, $file);
                                                $paths = str_replace('%SEX%', $item->sex, $path);
                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                            }
                                        }
                                    ?>
                                        <input type="radio" id="<?php echo $id; ?>-<?php echo $short; ?>" name="<?php echo $id; ?>"<?php echo (!empty($item->sex) ? ' data-required="sex=' . $item->sex . '"' : '') . $sex; ?>>
                                        <label for="<?php echo $id; ?>-<?php echo $short; ?>"><?php echo $item->name; ?><?php echo (!empty($item->sex) ? ' <small>(' . ucfirst($item->sex) . ' only)</small>' : ''); ?></label>
                                <?php if(!empty($item->opts)): ?>
                                    </span>
                                    <ul>
                                        <?php foreach($item->opts as $opt => $val): ?>
                                            <?php
                                                if(empty($val->short)) {
                                                    $val->short = str_replace(' ', '', strtolower($opt));
                                                }
                                                $file    = (!empty($val->file) ? $val->file : $val->short);
                                                $file    = str_replace('%SHORT%', $short, $file);
                                                $filem   = str_replace('%SEX%', 'male', $file);
                                                $filef   = str_replace('%SEX%', 'female', $file);

                                                $path2   = (!empty($val->path) ? $val->path : $path);
                                                $path2m  = str_replace('%SEX%', 'male', $path2);
                                                $path2f  = str_replace('%SEX%', 'female', $path2);

                                                $sex     = ' data-file_male="' . $path2m . '/' . $filem . '.png" data-file_female="' . $path2f . '/' . $filef . '.png"';
                                                if(!empty($item->sex)) {
                                                    $files = str_replace('%SEX%', $item->sex, $file);
                                                    $paths = str_replace('%SEX%', $item->sex, $path2);
                                                    $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                }
                                            ?>
                                            <li>
                                                <input type="checkbox" id="item<?php echo $short; ?>-<?php echo $val->short; ?>" name="item<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="item=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                <label for="item<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            <?php else:
                                $colors = $parts->item->colors;
                                if(!empty($item->colors)) {
                                    $colors = $item->colors;
                                }
                            ?>
                                <span class="condensed"><?php echo $item->name; ?><?php echo (!empty($item->sex) ? ' <small>(' . ucfirst($item->sex) . ' only)</small>' : ''); ?></span>
                                <ul>
                                    <?php foreach($item->colors as $color): ?>
                                        <?php
                                            $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                            $file     = (!empty($item->file) ? $item->file : $slug . '_' . $short . '_%SEX%');
                                            $file     = str_replace('%COLOR%', $slug, $file);
                                            $file     = str_replace('%SHORT%', $short, $file);
                                            $filem    = str_replace('%SEX%', 'male', $file);
                                            $filef    = str_replace('%SEX%', 'female', $file);

                                            $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                            if(!empty($item->sex)) {
                                                $files = str_replace('%SEX%', $item->sex, $file);
                                                $paths = str_replace('%SEX%', $item->sex, $path);
                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                $sex  .= ' data-required="sex=' . $item->sex . '"';
                                            }
                                        ?>
                                        <li>
                                            <input type="radio" id="<?php echo $id; ?>-<?php echo $short; ?>_<?php echo $slug; ?>" name="<?php echo $id; ?>"<?php echo $sex; ?>>
                                            <label for="<?php echo $id; ?>-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif;
    }


    /**
      * Output Body Type Listing
      *
      * @author JaidynReiman; May 2014
      */
    function output_body() {
        global $parts;

        if(!empty($parts->body->colors)): ?>
            <li><span class="condensed">Body</span>
                <ul>
                    <?php foreach($parts->body->colors as $k => $color): ?>
                        <?php
                            $short    = (!empty($color->short) ? $color->short : str_replace(' ', '', strtolower($color->name)));
                            $file     = $short;
                            if(!empty($color->file)) {
                                $file = $color->file;
                            }

                            if(!empty($color->sex)) {
                                $sex  = ' data-required="sex=' . $color->sex . '" data-file_' . $color->sex . '="body/' . $color->sex . '/' . $file . '.png"';
                            }
                            else {
                                $sex  = ' data-file_female="body/female/' . $file . '.png" data-file_male="body/male/' . $file . '.png"';
                            }

                            $shadows  = '';
                            if((empty($color->shadows) || $color->shadows != 'none') && !empty($parts->hair->styles)) {
                                foreach($parts->hair->styles as $style) {
                                    if(!empty($style->shadows)) {
                                        $hair = (!empty($style->short) ? $style->short : str_replace(' ', '', strtolower($style->name)));
                                        if(!empty($color->sex)) {
                                            $shadows = ' data-hs_' . $hair . '_' . $color->sex . '="body/' . $color->sex . '/' . $hair . '/shadows-' . $file . 'body.png"';
                                        }
                                        else {
                                            $shadows = ' data-hs_' . $hair . '_female="hair/female/' . $hair . '/shadows-' . $short . 'body.png" data-hs_' . $file . '_male="hair/male/' . $hair . '/shadows-' . $short . 'body.png"';
                                        }
                                    }
                                }
                            }
                        ?>
                        <li>
                            <input type="radio" id="body-<?php echo $short; ?>" name="body"<?php echo (empty($k) ? ' checked' : ''); ?><?php echo $sex . $shadows; ?>>
                            <label for="body-<?php echo $short; ?>"><?php echo $color->name; ?><?php echo (!empty($color->sex) ? ' (' . ucfirst($color->sex) . ' only)' : ''); ?></label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif;
    }


    /**
      * Output Eyes Listing
      *
      * @author JaidynReiman; May 2014
      */
    function output_eyes() {
        global $parts;

        if(!empty($parts->body->eyes)): ?>
            <li><span class="condensed">Eyes</span>
                <ul>
                    <li>
                        <input type="radio" id="eyes-none" name="eyes" checked>
                        <label for="eyes-none">Default</label>
                    </li>
                    <?php foreach($parts->body->eyes as $k => $color): ?>
                        <?php
                            $short    = str_replace(' ', '', strtolower($color));

                            if(!empty($color->sex)) {
                                $sex  = ' data-file_' . $color->sex . '="body/' . $color->sex . '/eyes/' . $short . '.png"';
                            }
                            else {
                                $sex  = ' data-file_female="body/female/eyes/' . $short . '.png" data-file_male="body/male/eyes/' . $short . '.png"';
                            }
                        ?>
                        <li>
                            <input type="radio" id="eyes-<?php echo $short; ?>" name="eyes"<?php echo $sex; ?>>
                            <label for="eyes-<?php echo $short; ?>"><?php echo $color; ?></label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif;
    }


    /**
      * Output Nose Listing
      *
      * @author JaidynReiman; May 2014
      */
    function output_nose() {
        global $parts;

        if(!empty($parts->body->nose)): ?>
            <li><span class="condensed">Nose</span>
                <ul>
                    <li>
                        <input type="radio" id="nose-none" name="nose" checked>
                        <label for="nose-none">Default</label>
                    </li>
                    <?php foreach($parts->body->nose as $nose): ?>
                        <?php
                            $short = (!empty($nose->short) ? $nose->short : str_replace(' ', '', strtolower($nose->name)));
                            $colors = '';
                            $prohibition = '';
                            foreach($nose->prohibited as $color) {
                                if(!empty($prohibition)) {
                                    $prohibition .= ',';
                                }
                                $prohibition .= 'body=' . $color;
                            }
                            foreach($parts->body->colors as $color) {
                                $body = (!empty($color->short) ? $color->short : str_replace(' ', '', strtolower($color->name)));
                                if(in_array($body, $nose->prohibited)) {
                                    continue;
                                }
                                $colors .= ' data-file_male_' . $body . '="body/male/nose/' . $short . '_' . $body . '.png" data-file_female_' . $body . '="body/female/nose/' . $short . '_' . $body . '.png"';
                            }
                            if(!empty($prohibition)) {
                                $prohibition = ' data-prohibited="' . $prohibition . '"';
                            }
                        ?>
                        <li>
                            <input type="radio" id="nose-<?php echo $short; ?>" name="nose"<?php echo $prohibition . $colors; ?>>
                            <label for="nose-<?php echo $short; ?>"><?php echo $nose->name; ?></label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif;
    }

?>