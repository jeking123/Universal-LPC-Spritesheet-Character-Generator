<?php
    $data  = file_get_contents(dirname(__FILE__) . "/parts.json");
    $parts = json_decode($data);
?><!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
        <title>Universal LPC Sprite Sheet Character Generator</title>
        <link rel="stylesheet" type="text/css" href="chargen.css">
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.string/2.3.3/underscore.string.min.js"></script>
        <script type="text/javascript" src="jhash-2.1.min.js"></script>
        <script type="text/javascript" src="canvas2image.js"></script>
        <script type="text/javascript" src="base64.js"></script>
        <script type="text/javascript" src="chargen.js"></script>
    </head>
    <body>
        <div id="github"><a href="https://github.com/gaurav0/Universal-LPC-Spritesheet-Character-Generator"><img src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png" alt="Fork me on GitHub"></a></div>
        <header>
            <h1>Character Generator</h1>
            <p class="subtitle">Create a character sprite sheet for your game using 100% open art.</p>
            <h2>Based on <a href="https://github.com/jrconway3/Universal-LPC-spritesheet">Universal LPC Sprite Sheet</a></h2>
            <h3>All art is dual licensed: GPL3 and CC-BY-SA3</h3>
        </header>
        <div id="previewAnimationsBox">
            Preview Animation: 
            <select id="whichAnim">
                <option value="spellcast" data-row="0" data-num="4" data-cycle="7">Spellcast</option>
                <option value="thrust" data-row="4" data-num="4" data-cycle="8">Thrust</option>
                <option value="walk" selected data-row="8" data-num="4" data-cycle="9">Walk</option>
                <option value="slash" data-row="12" data-num="4" data-cycle="6">Slash</option>
                <option value="shoot" data-row="16" data-num="4" data-cycle="13">Shoot</option>
                <option value="hurt" data-row="20" data-num="1" data-cycle="6">Hurt</option>
            </select>
            <canvas id="previewAnimations" width="256" height="192"></canvas>
        </div>
        <form id="customizeChar">
            <section id="chooser">
                <p class="instr">Select from the character configuration options below.</p>
                <ul>
                    <li><span class="condensed">Sex</span>
                        <ul>
                            <li>
                                <input type="radio" id="sex-male" name="sex" checked>
                                <label for="sex-male">Male</label>
                            </li>
                            <li>
                            <li>
                                <input type="radio" id="sex-female" name="sex">
                                <label for="sex-female">Female</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Body</span>
                        <ul>
                            <?php if(!empty($parts->body->colors)): ?>
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
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Eyes</span>
                        <ul>
                            <li>
                                <input type="radio" id="eyes-none" name="eyes" checked>
                                <label for="eyes-none">Default</label>
                            </li>
                            <?php if(!empty($parts->body->eyes)): ?>
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
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Nose</span>
                        <ul>
                            <li>
                                <input type="radio" id="nose-none" name="nose" checked>
                                <label for="nose-none">Default</label>
                            </li>
                            <?php if(!empty($parts->body->nose)): ?>
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
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <span class="condensed">Legs</span>
                        <ul>
                            <li>
                                <input type="radio" id="legs-none" name="legs" checked>
                                <label for="legs-none">No Legs</label>
                            </li>
                            <?php if(!empty($parts->clothes->legs)): ?>
                                <?php foreach($parts->clothes->legs as $legs): ?>
                                    <?php
                                        $short = (!empty($legs->short) ? $legs->short : str_replace(' ', '', strtolower($legs->name)));
                                        $path  = (!empty($legs->path) ? $legs->path : 'legs');
                                        if(empty($legs->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($legs->colors) && $legs->colors == 'none') || (empty($parts->legs->colors) && empty($legs->colors))): ?>
                                            <?php if(!empty($legs->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($legs->file) ? $legs->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($legs->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($legs->sex)) {
                                                            $files = str_replace('%SEX%', $legs->sex, $file);
                                                            $paths = str_replace('%SEX%', $legs->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="legs-<?php echo $short; ?>" name="legs"<?php echo (!empty($legs->sex) ? ' data-required="sex=' . $legs->sex . '"' : '') . $sex; ?>>
                                                    <label for="legs-<?php echo $short; ?>"><?php echo $legs->name; ?><?php echo (!empty($legs->sex) ? ' <small>(' . ucfirst($legs->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($legs->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($legs->opts as $opt => $val): ?>
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
                                                            if(!empty($legs->sex)) {
                                                                $files = str_replace('%SEX%', $legs->sex, $file);
                                                                $paths = str_replace('%SEX%', $legs->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="legs<?php echo $short; ?>-<?php echo $val->short; ?>" name="legs<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="legs=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="legs<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->legs->colors;
                                            if(!empty($legs->colors)) {
                                                $colors = $legs->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $legs->name; ?><?php echo (!empty($legs->sex) ? ' <small>(' . ucfirst($legs->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($legs->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($legs->file) ? $legs->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($legs->sex)) {
                                                            $files = str_replace('%SEX%', $legs->sex, $file);
                                                            $paths = str_replace('%SEX%', $legs->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $legs->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="legs-<?php echo $short; ?>_<?php echo $slug; ?>" name="legs"<?php echo $sex; ?>>
                                                        <label for="legs-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Clothes</span>
                        <ul>
                            <li>
                                <input type="radio" id="clothes-none" name="clothes" checked>
                                <label for="clothes-none">No Clothes</label>
                            </li>
                            <?php if(!empty($parts->clothes->torso)): ?>
                                <?php foreach($parts->clothes->torso as $torso): ?>
                                    <?php
                                        $short = (!empty($torso->short) ? $torso->short : str_replace(' ', '', strtolower($torso->name)));
                                        $path  = (!empty($torso->path) ? $torso->path : 'torso');
                                        if(empty($torso->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($torso->colors) && $torso->colors == 'none') || (empty($parts->torso->colors) && empty($torso->colors))): ?>
                                            <?php if(!empty($torso->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($torso->file) ? $torso->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($torso->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($torso->sex)) {
                                                            $files = str_replace('%SEX%', $torso->sex, $file);
                                                            $paths = str_replace('%SEX%', $torso->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="clothes-<?php echo $short; ?>" name="clothes"<?php echo (!empty($torso->sex) ? ' data-required="sex=' . $torso->sex . '"' : '') . $sex; ?>>
                                                    <label for="clothes-<?php echo $short; ?>"><?php echo $torso->name; ?><?php echo (!empty($torso->sex) ? ' <small>(' . ucfirst($torso->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($torso->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($torso->opts as $opt => $val): ?>
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
                                                            if(!empty($torso->sex)) {
                                                                $files = str_replace('%SEX%', $torso->sex, $file);
                                                                $paths = str_replace('%SEX%', $torso->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="clothes<?php echo $short; ?>-<?php echo $val->short; ?>" name="clothes<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="clothes=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="clothes<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->torso->colors;
                                            if(!empty($torso->colors)) {
                                                $colors = $torso->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $torso->name; ?><?php echo (!empty($torso->sex) ? ' <small>(' . ucfirst($torso->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($torso->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($torso->file) ? $torso->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($torso->sex)) {
                                                            $files = str_replace('%SEX%', $torso->sex, $file);
                                                            $paths = str_replace('%SEX%', $torso->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $torso->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="clothes-<?php echo $short; ?>_<?php echo $slug; ?>" name="clothes"<?php echo $sex; ?>>
                                                        <label for="clothes-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Mail</span>
                        <ul>
                            <li>
                                <input type="radio" id="mail-none" name="mail" checked>
                                <label for="mail-none">No Mail</label>
                            </li>
                            <?php if(!empty($parts->armor->mail)): ?>
                                <?php foreach($parts->armor->mail as $mail): ?>
                                    <?php
                                        $short = (!empty($mail->short) ? $mail->short : str_replace(' ', '', strtolower($mail->name)));
                                        $path  = (!empty($mail->path) ? $mail->path : 'torso/chain');
                                        if(empty($mail->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($mail->colors) && $mail->colors == 'none') || (empty($parts->armor->colors) && empty($mail->colors))): ?>
                                            <?php if(!empty($mail->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($mail->file) ? $mail->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($mail->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($mail->sex)) {
                                                            $files = str_replace('%SEX%', $mail->sex, $file);
                                                            $paths = str_replace('%SEX%', $mail->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="mail-<?php echo $short; ?>" name="mail"<?php echo (!empty($mail->sex) ? ' data-required="sex=' . $mail->sex . '"' : '') . $sex; ?>>
                                                    <label for="mail-<?php echo $short; ?>"><?php echo $mail->name; ?><?php echo (!empty($mail->sex) ? ' <small>(' . ucfirst($mail->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($mail->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($mail->opts as $opt => $val): ?>
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
                                                            if(!empty($mail->sex)) {
                                                                $files = str_replace('%SEX%', $mail->sex, $file);
                                                                $paths = str_replace('%SEX%', $mail->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="mail<?php echo $short; ?>-<?php echo $val->short; ?>" name="mail<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="mail=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="mail<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($mail->colors)) {
                                                $colors = $mail->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $mail->name; ?><?php echo (!empty($mail->sex) ? ' <small>(' . ucfirst($mail->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($mail->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($mail->file) ? $mail->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($mail->sex)) {
                                                            $files = str_replace('%SEX%', $mail->sex, $file);
                                                            $paths = str_replace('%SEX%', $mail->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $mail->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="mail-<?php echo $short; ?>_<?php echo $slug; ?>" name="mail"<?php echo $sex; ?>>
                                                        <label for="mail-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Armor</span>
                        <ul>
                            <li>
                                <input type="radio" id="armor-none" name="armor" checked>
                                <label for="armor-none">No Armor</label>
                            </li>
                            <?php if(!empty($parts->armor->chest)): ?>
                                <?php foreach($parts->armor->chest as $chest): ?>
                                    <?php
                                        $short = (!empty($chest->short) ? $chest->short : str_replace(' ', '', strtolower($chest->name)));
                                        $path  = (!empty($chest->path) ? $chest->path : 'torso/chain');
                                        if(empty($chest->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($chest->colors) && $chest->colors == 'none') || (empty($parts->armor->colors) && empty($chest->colors))): ?>
                                            <?php if(!empty($chest->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($chest->file) ? $chest->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($chest->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($chest->sex)) {
                                                            $files = str_replace('%SEX%', $chest->sex, $file);
                                                            $paths = str_replace('%SEX%', $chest->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="armor-<?php echo $short; ?>" name="armor"<?php echo (!empty($chest->sex) ? ' data-required="sex=' . $chest->sex . '"' : '') . $sex; ?>>
                                                    <label for="armor-<?php echo $short; ?>"><?php echo $chest->name; ?><?php echo (!empty($chest->sex) ? ' <small>(' . ucfirst($chest->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($chest->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($chest->opts as $opt => $val): ?>
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
                                                            if(!empty($chest->sex)) {
                                                                $files = str_replace('%SEX%', $chest->sex, $file);
                                                                $paths = str_replace('%SEX%', $chest->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="armor<?php echo $short; ?>-<?php echo $val->short; ?>" name="armor<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="armor=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="armor<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($chest->colors)) {
                                                $colors = $chest->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $chest->name; ?><?php echo (!empty($chest->sex) ? ' <small>(' . ucfirst($chest->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($chest->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($chest->file) ? $chest->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($chest->sex)) {
                                                            $files = str_replace('%SEX%', $chest->sex, $file);
                                                            $paths = str_replace('%SEX%', $chest->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $chest->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="armor-<?php echo $short; ?>_<?php echo $slug; ?>" name="armor"<?php echo $sex; ?>>
                                                        <label for="armor-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Jacket</span>
                        <ul>
                            <li>
                                <input type="radio" id="jacket-none" name="jacket" checked>
                                <label for="jacket-none">No Jacket</label>
                            </li>
                            <?php if(!empty($parts->armor->jacket)): ?>
                                <?php foreach($parts->armor->jacket as $jacket): ?>
                                    <?php
                                        $short = (!empty($jacket->short) ? $jacket->short : str_replace(' ', '', strtolower($jacket->name)));
                                        $path  = (!empty($jacket->path) ? $jacket->path : 'torso/chain');
                                        if(empty($jacket->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($jacket->colors) && $jacket->colors == 'none') || (empty($parts->armor->colors) && empty($jacket->colors))): ?>
                                            <?php if(!empty($jacket->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($jacket->file) ? $jacket->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($jacket->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($jacket->sex)) {
                                                            $files = str_replace('%SEX%', $jacket->sex, $file);
                                                            $paths = str_replace('%SEX%', $jacket->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="armor-<?php echo $short; ?>" name="armor"<?php echo (!empty($jacket->sex) ? ' data-required="sex=' . $jacket->sex . '"' : '') . $sex; ?>>
                                                    <label for="armor-<?php echo $short; ?>"><?php echo $jacket->name; ?><?php echo (!empty($jacket->sex) ? ' <small>(' . ucfirst($jacket->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($jacket->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($jacket->opts as $opt => $val): ?>
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
                                                            if(!empty($jacket->sex)) {
                                                                $files = str_replace('%SEX%', $jacket->sex, $file);
                                                                $paths = str_replace('%SEX%', $jacket->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="armor<?php echo $short; ?>-<?php echo $val->short; ?>" name="armor<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="armor=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="armor<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($jacket->colors)) {
                                                $colors = $jacket->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $jacket->name; ?><?php echo (!empty($jacket->sex) ? ' <small>(' . ucfirst($jacket->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($jacket->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($jacket->file) ? $jacket->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($jacket->sex)) {
                                                            $files = str_replace('%SEX%', $jacket->sex, $file);
                                                            $paths = str_replace('%SEX%', $jacket->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $jacket->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="armor-<?php echo $short; ?>_<?php echo $slug; ?>" name="armor"<?php echo $sex; ?>>
                                                        <label for="armor-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Neck</span>
                        <ul>
                            <li>
                                <input type="radio" id="neck-none" name="tie" checked>
                                <label for="neck-none">No Neck Accessory</label>
                            </li>
                            <?php if(!empty($parts->clothes->neck)): ?>
                                <?php foreach($parts->clothes->neck as $neck): ?>
                                    <?php
                                        $short = (!empty($neck->short) ? $neck->short : str_replace(' ', '', strtolower($neck->name)));
                                        $path  = (!empty($neck->path) ? $neck->path : 'accessories/neck');
                                        if(empty($neck->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($neck->colors) && $neck->colors == 'none') || (empty($parts->clothes->colors) && empty($neck->colors))): ?>
                                            <?php if(!empty($neck->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($neck->file) ? $neck->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($neck->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($neck->sex)) {
                                                            $files = str_replace('%SEX%', $neck->sex, $file);
                                                            $paths = str_replace('%SEX%', $neck->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="neck-<?php echo $short; ?>" name="neck"<?php echo (!empty($neck->sex) ? ' data-required="sex=' . $neck->sex . '"' : '') . $sex; ?>>
                                                    <label for="neck-<?php echo $short; ?>"><?php echo $neck->name; ?><?php echo (!empty($neck->sex) ? ' <small>(' . ucfirst($neck->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($neck->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($neck->opts as $opt => $val): ?>
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
                                                            if(!empty($neck->sex)) {
                                                                $files = str_replace('%SEX%', $neck->sex, $file);
                                                                $paths = str_replace('%SEX%', $neck->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="neck<?php echo $short; ?>-<?php echo $val->short; ?>" name="neck<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="neck=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="neck<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->clothes->colors;
                                            if(!empty($neck->colors)) {
                                                $colors = $neck->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $neck->name; ?><?php echo (!empty($neck->sex) ? ' <small>(' . ucfirst($neck->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($neck->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($neck->file) ? $neck->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($neck->sex)) {
                                                            $files = str_replace('%SEX%', $neck->sex, $file);
                                                            $paths = str_replace('%SEX%', $neck->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $neck->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="neck-<?php echo $short; ?>_<?php echo $slug; ?>" name="neck"<?php echo $sex; ?>>
                                                        <label for="neck-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Arms</span>
                        <ul>
                            <li>
                                <input type="radio" id="arms-none" name="arms" checked>
                                <label for="arms-none">No Arms</label>
                            </li>
                            <?php if(!empty($parts->armor->arms)): ?>
                                <?php foreach($parts->armor->arms as $arms): ?>
                                    <?php
                                        $short = (!empty($arms->short) ? $arms->short : str_replace(' ', '', strtolower($arms->name)));
                                        $path  = (!empty($arms->path) ? $arms->path : 'torso');
                                        if(empty($arms->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($arms->colors) && $arms->colors == 'none') || (empty($parts->armor->colors) && empty($arms->colors))): ?>
                                            <?php if(!empty($arms->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($arms->file) ? $arms->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($arms->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($arms->sex)) {
                                                            $files = str_replace('%SEX%', $arms->sex, $file);
                                                            $paths = str_replace('%SEX%', $arms->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="arms-<?php echo $short; ?>" name="arms"<?php echo (!empty($arms->sex) ? ' data-required="sex=' . $arms->sex . '"' : '') . $sex; ?>>
                                                    <label for="arms-<?php echo $short; ?>"><?php echo $arms->name; ?><?php echo (!empty($arms->sex) ? ' <small>(' . ucfirst($arms->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($arms->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($arms->opts as $opt => $val): ?>
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
                                                            if(!empty($arms->sex)) {
                                                                $files = str_replace('%SEX%', $arms->sex, $file);
                                                                $paths = str_replace('%SEX%', $arms->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="arms<?php echo $short; ?>-<?php echo $val->short; ?>" name="arms<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="arms=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="arms<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($arms->colors)) {
                                                $colors = $arms->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $arms->name; ?><?php echo (!empty($arms->sex) ? ' <small>(' . ucfirst($arms->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($arms->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($arms->file) ? $arms->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($arms->sex)) {
                                                            $files = str_replace('%SEX%', $arms->sex, $file);
                                                            $paths = str_replace('%SEX%', $arms->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $arms->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="arms-<?php echo $short; ?>_<?php echo $slug; ?>" name="arms"<?php echo $sex; ?>>
                                                        <label for="arms-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Shoulders</span>
                        <ul>
                            <li>
                                <input type="radio" id="shoulders-none" name="shoulders" checked>
                                <label for="shoulders-none">No Shoulders</label>
                            </li>
                            <?php if(!empty($parts->armor->shoulders)): ?>
                                <?php foreach($parts->armor->shoulders as $shoulders): ?>
                                    <?php
                                        $short = (!empty($shoulders->short) ? $shoulders->short : str_replace(' ', '', strtolower($shoulders->name)));
                                        $path  = (!empty($shoulders->path) ? $shoulders->path : 'torso');
                                        if(empty($shoulders->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($shoulders->colors) && $shoulders->colors == 'none') || (empty($parts->armor->colors) && empty($shoulders->colors))): ?>
                                            <?php if(!empty($shoulders->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($shoulders->file) ? $shoulders->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($shoulders->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($shoulders->sex)) {
                                                            $files = str_replace('%SEX%', $shoulders->sex, $file);
                                                            $paths = str_replace('%SEX%', $shoulders->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="shoulders-<?php echo $short; ?>" name="shoulders"<?php echo (!empty($shoulders->sex) ? ' data-required="sex=' . $shoulders->sex . '"' : '') . $sex; ?>>
                                                    <label for="shoulders-<?php echo $short; ?>"><?php echo $shoulders->name; ?><?php echo (!empty($shoulders->sex) ? ' <small>(' . ucfirst($shoulders->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($shoulders->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($shoulders->opts as $opt => $val): ?>
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
                                                            if(!empty($shoulders->sex)) {
                                                                $files = str_replace('%SEX%', $shoulders->sex, $file);
                                                                $paths = str_replace('%SEX%', $shoulders->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="shoulders<?php echo $short; ?>-<?php echo $val->short; ?>" name="shoulders<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="shoulders=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="shoulders<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($shoulders->colors)) {
                                                $colors = $shoulders->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $shoulders->name; ?><?php echo (!empty($shoulders->sex) ? ' <small>(' . ucfirst($shoulders->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($shoulders->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($shoulders->file) ? $shoulders->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($shoulders->sex)) {
                                                            $files = str_replace('%SEX%', $shoulders->sex, $file);
                                                            $paths = str_replace('%SEX%', $shoulders->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $shoulders->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="shoulders-<?php echo $short; ?>_<?php echo $slug; ?>" name="shoulders"<?php echo $sex; ?>>
                                                        <label for="shoulders-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Spikes</span>
                        <ul>
                            <li>
                                <input type="radio" id="spikes-none" name="spikes" checked>
                                <label for="spikes-none">No Spikes</label>
                            </li>
                            <?php if(!empty($parts->armor->spikes)): ?>
                                <?php foreach($parts->armor->spikes as $spikes): ?>
                                    <?php
                                        $short = (!empty($spikes->short) ? $spikes->short : str_replace(' ', '', strtolower($spikes->name)));
                                        $path  = (!empty($spikes->path) ? $spikes->path : 'torso');
                                        if(empty($spikes->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($spikes->colors) && $spikes->colors == 'none') || (empty($parts->armor->colors) && empty($spikes->colors))): ?>
                                            <?php if(!empty($spikes->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($spikes->file) ? $spikes->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($spikes->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($spikes->sex)) {
                                                            $files = str_replace('%SEX%', $spikes->sex, $file);
                                                            $paths = str_replace('%SEX%', $spikes->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="spikes-<?php echo $short; ?>" name="spikes"<?php echo (!empty($spikes->sex) ? ' data-required="sex=' . $spikes->sex . '"' : '') . $sex; ?>>
                                                    <label for="spikes-<?php echo $short; ?>"><?php echo $spikes->name; ?><?php echo (!empty($spikes->sex) ? ' <small>(' . ucfirst($spikes->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($spikes->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($spikes->opts as $opt => $val): ?>
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
                                                            if(!empty($spikes->sex)) {
                                                                $files = str_replace('%SEX%', $spikes->sex, $file);
                                                                $paths = str_replace('%SEX%', $spikes->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="spikes<?php echo $short; ?>-<?php echo $val->short; ?>" name="spikes<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="spikes=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="spikes<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($spikes->colors)) {
                                                $colors = $spikes->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $spikes->name; ?><?php echo (!empty($spikes->sex) ? ' <small>(' . ucfirst($spikes->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($spikes->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($spikes->file) ? $spikes->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($spikes->sex)) {
                                                            $files = str_replace('%SEX%', $spikes->sex, $file);
                                                            $paths = str_replace('%SEX%', $spikes->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $spikes->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="spikes-<?php echo $short; ?>_<?php echo $slug; ?>" name="spikes"<?php echo $sex; ?>>
                                                        <label for="spikes-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Bracers</span>
                        <ul>
                            <li>
                                <input type="radio" id="bracers-none" name="bracers" checked>
                                <label for="bracers-none">No Bracers</label>
                            </li>
                            <?php if(!empty($parts->armor->bracers)): ?>
                                <?php foreach($parts->armor->bracers as $bracers): ?>
                                    <?php
                                        $short = (!empty($bracers->short) ? $bracers->short : str_replace(' ', '', strtolower($bracers->name)));
                                        $path  = (!empty($bracers->path) ? $bracers->path : 'hands/bracers');
                                        if(empty($bracers->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($bracers->colors) && $bracers->colors == 'none') || (empty($parts->armor->colors) && empty($bracers->colors))): ?>
                                            <?php if(!empty($bracers->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($bracers->file) ? $bracers->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($bracers->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($bracers->sex)) {
                                                            $files = str_replace('%SEX%', $bracers->sex, $file);
                                                            $paths = str_replace('%SEX%', $bracers->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="bracers-<?php echo $short; ?>" name="bracers"<?php echo (!empty($bracers->sex) ? ' data-required="sex=' . $bracers->sex . '"' : '') . $sex; ?>>
                                                    <label for="bracers-<?php echo $short; ?>"><?php echo $bracers->name; ?><?php echo (!empty($bracers->sex) ? ' <small>(' . ucfirst($bracers->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($bracers->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($bracers->opts as $opt => $val): ?>
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
                                                            if(!empty($bracers->sex)) {
                                                                $files = str_replace('%SEX%', $bracers->sex, $file);
                                                                $paths = str_replace('%SEX%', $bracers->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="bracers<?php echo $short; ?>-<?php echo $val->short; ?>" name="bracers<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="bracers=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="bracers<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($bracers->colors)) {
                                                $colors = $bracers->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $bracers->name; ?><?php echo (!empty($bracers->sex) ? ' <small>(' . ucfirst($bracers->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($bracers->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($bracers->file) ? $bracers->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($bracers->sex)) {
                                                            $files = str_replace('%SEX%', $bracers->sex, $file);
                                                            $paths = str_replace('%SEX%', $bracers->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $bracers->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="bracers-<?php echo $short; ?>_<?php echo $slug; ?>" name="bracers"<?php echo $sex; ?>>
                                                        <label for="bracers-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Greaves</span>
                        <ul>
                            <li>
                                <input type="radio" id="greaves-none" name="greaves" checked>
                                <label for="greaves-none">No Greaves</label>
                            </li>
                            <?php if(!empty($parts->armor->greaves)): ?>
                                <?php foreach($parts->armor->greaves as $greaves): ?>
                                    <?php
                                        $short = (!empty($greaves->short) ? $greaves->short : str_replace(' ', '', strtolower($greaves->name)));
                                        $path  = (!empty($greaves->path) ? $greaves->path : 'legs/armor');
                                        if(empty($greaves->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($greaves->colors) && $greaves->colors == 'none') || (empty($parts->armor->colors) && empty($greaves->colors))): ?>
                                            <?php if(!empty($greaves->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($greaves->file) ? $greaves->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($greaves->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($greaves->sex)) {
                                                            $files = str_replace('%SEX%', $greaves->sex, $file);
                                                            $paths = str_replace('%SEX%', $greaves->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="greaves-<?php echo $short; ?>" name="greaves"<?php echo (!empty($greaves->sex) ? ' data-required="sex=' . $greaves->sex . '"' : '') . $sex; ?>>
                                                    <label for="greaves-<?php echo $short; ?>"><?php echo $greaves->name; ?><?php echo (!empty($greaves->sex) ? ' <small>(' . ucfirst($greaves->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($greaves->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($greaves->opts as $opt => $val): ?>
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
                                                            if(!empty($greaves->sex)) {
                                                                $files = str_replace('%SEX%', $greaves->sex, $file);
                                                                $paths = str_replace('%SEX%', $greaves->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="greaves<?php echo $short; ?>-<?php echo $val->short; ?>" name="greaves<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="greaves=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="greaves<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($greaves->colors)) {
                                                $colors = $greaves->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $greaves->name; ?><?php echo (!empty($greaves->sex) ? ' <small>(' . ucfirst($greaves->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($greaves->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($greaves->file) ? $greaves->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($greaves->sex)) {
                                                            $files = str_replace('%SEX%', $greaves->sex, $file);
                                                            $paths = str_replace('%SEX%', $greaves->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $greaves->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="greaves-<?php echo $short; ?>_<?php echo $slug; ?>" name="greaves"<?php echo $sex; ?>>
                                                        <label for="greaves-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Gloves</span>
                        <ul>
                            <li>
                                <input type="radio" id="gloves-none" name="gloves" checked>
                                <label for="gloves-none">No Gloves</label>
                            </li>
                            <?php if(!empty($parts->armor->gloves)): ?>
                                <?php foreach($parts->armor->gloves as $gloves): ?>
                                    <?php
                                        $short = (!empty($gloves->short) ? $gloves->short : str_replace(' ', '', strtolower($gloves->name)));
                                        $path  = (!empty($gloves->path) ? $gloves->path : 'hands/gloves');
                                        if(empty($gloves->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($gloves->colors) && $gloves->colors == 'none') || (empty($parts->armor->colors) && empty($gloves->colors))): ?>
                                            <?php if(!empty($gloves->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($gloves->file) ? $gloves->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($gloves->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($gloves->sex)) {
                                                            $files = str_replace('%SEX%', $gloves->sex, $file);
                                                            $paths = str_replace('%SEX%', $gloves->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="gloves-<?php echo $short; ?>" name="gloves"<?php echo (!empty($gloves->sex) ? ' data-required="sex=' . $gloves->sex . '"' : '') . $sex; ?>>
                                                    <label for="gloves-<?php echo $short; ?>"><?php echo $gloves->name; ?><?php echo (!empty($gloves->sex) ? ' <small>(' . ucfirst($gloves->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($gloves->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($gloves->opts as $opt => $val): ?>
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
                                                            if(!empty($gloves->sex)) {
                                                                $files = str_replace('%SEX%', $gloves->sex, $file);
                                                                $paths = str_replace('%SEX%', $gloves->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="gloves<?php echo $short; ?>-<?php echo $val->short; ?>" name="gloves<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="gloves=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="gloves<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($gloves->colors)) {
                                                $colors = $gloves->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $gloves->name; ?><?php echo (!empty($gloves->sex) ? ' <small>(' . ucfirst($gloves->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($gloves->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($gloves->file) ? $gloves->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($gloves->sex)) {
                                                            $files = str_replace('%SEX%', $gloves->sex, $file);
                                                            $paths = str_replace('%SEX%', $gloves->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $gloves->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="gloves-<?php echo $short; ?>_<?php echo $slug; ?>" name="gloves"<?php echo $sex; ?>>
                                                        <label for="gloves-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Shoes</span>
                        <ul>
                            <li>
                                <input type="radio" id="shoes-none" name="shoes" checked>
                                <label for="shoes-none">No Shoes</label>
                            </li>
                            <?php if(!empty($parts->clothes->shoes)): ?>
                                <?php foreach($parts->clothes->shoes as $shoes): ?>
                                    <?php
                                        $short = (!empty($shoes->short) ? $shoes->short : str_replace(' ', '', strtolower($shoes->name)));
                                        $path  = (!empty($shoes->path) ? $shoes->path : 'feet');
                                        if(empty($shoes->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($shoes->colors) && $shoes->colors == 'none') || (empty($parts->armor->colors) && empty($shoes->colors))): ?>
                                            <?php if(!empty($shoes->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($shoes->file) ? $shoes->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($shoes->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($shoes->sex)) {
                                                            $files = str_replace('%SEX%', $shoes->sex, $file);
                                                            $paths = str_replace('%SEX%', $shoes->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="shoes-<?php echo $short; ?>" name="shoes"<?php echo (!empty($shoes->sex) ? ' data-required="sex=' . $shoes->sex . '"' : '') . $sex; ?>>
                                                    <label for="shoes-<?php echo $short; ?>"><?php echo $shoes->name; ?><?php echo (!empty($shoes->sex) ? ' <small>(' . ucfirst($shoes->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($shoes->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($shoes->opts as $opt => $val): ?>
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
                                                            if(!empty($shoes->sex)) {
                                                                $files = str_replace('%SEX%', $shoes->sex, $file);
                                                                $paths = str_replace('%SEX%', $shoes->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="shoes<?php echo $short; ?>-<?php echo $val->short; ?>" name="shoes<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="shoes=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="shoes<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->armor->colors;
                                            if(!empty($shoes->colors)) {
                                                $colors = $shoes->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $shoes->name; ?><?php echo (!empty($shoes->sex) ? ' <small>(' . ucfirst($shoes->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($shoes->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $file     = (!empty($shoes->file) ? $shoes->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($shoes->sex)) {
                                                            $files = str_replace('%SEX%', $shoes->sex, $file);
                                                            $paths = str_replace('%SEX%', $shoes->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $shoes->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="shoes-<?php echo $short; ?>_<?php echo $slug; ?>" name="shoes"<?php echo $sex; ?>>
                                                        <label for="shoes-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Belts</span>
                        <ul>
                            <li>
                                <input type="radio" id="belt-none" name="belt" checked>
                                <label for="belt-none">No Belts</label>
                            </li>
                            <li>
                                <input type="radio" id="belt-leather" name="belt" data-file_male="belt/leather/male/leather_male.png" data-file_female="belt/leather/female/leather_female.png">
                                <label for="belt-leather">Leather Belt</label>
                            </li>
                            <li><span class="condensed">Cloth Belt</span>
                                <ul>
                                    <li>
                                        <input type="radio" id="belt-cloth_white" name="belt" data-file_male="belt/cloth/male/white_cloth_male.png" data-file_female="belt/cloth/female/white_cloth_female.png">
                                        <label for="belt-cloth_white">White</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="belt-cloth_teal" name="belt" data-required="sex=female" data-file_female="belt/cloth/female/teal2_cloth_female.png">
                                        <label for="belt-cloth_teal">Teal <small>(Female only)</small></label>
                                    </li>
                                </ul>
                            </li>
                            <li><span class="condensed">Metal Belt <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="belt_black" name="belt" data-required="sex=female" data-file="belt/cloth/female/black_female_no_th-sh.png">
                                        <label for="belt_black">Black Belt <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                                    </li>
                                    <li>
                                        <input type="radio" id="belt_brown" name="belt" data-required="sex=female" data-file="belt/cloth/female/brown_female_no_th-sh.png">
                                        <label for="belt_brown">Brown Belt <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                                    </li>
                                    <li>
                                        <input type="radio" id="belt-bronze" name="belt" data-required="sex=female" data-file="belt/metal/female/bronze_female_no_th-sh.png">
                                        <label for="belt-bronze">Bronze Belt <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                                    </li>
                                    <li>
                                        <input type="radio" id="belt-iron" name="belt" data-required="sex=female" data-file="belt/metal/female/iron_female_no_th-sh.png">
                                        <label for="belt-iron">Iron Belt <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                                    </li>
                                    <li>
                                        <input type="radio" id="belt-silver" name="belt" data-required="sex=female" data-file="belt/metal/female/silver_female_no_th-sh.png">
                                        <label for="belt-silver">Silver Belt <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                                    </li>
                                    <li>
                                        <input type="radio" id="belt-gold" name="belt" data-required="sex=female" data-file="belt/metal/female/gold_female_no_th-sh.png">
                                        <label for="belt-gold">Gold Belt <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Buckles</span>
                        <ul>
                            <li>
                                <input type="radio" id="buckle-none" name="buckle" checked>
                                <label for="buckle-none">No Buckle</label>
                            </li>
                            <li>
                                <input type="radio" id="buckle-bronze" name="buckle" data-required="sex=female" data-file="belt/buckles_female_no_th-sh/bronze.png">
                                <label for="buckle-bronze">Bronze Buckle <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="buckle-iron" name="buckle" data-required="sex=female" data-file="belt/buckles_female_no_th-sh/iron.png">
                                <label for="buckle-iron">Iron Buckle <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="buckle-silver" name="buckle" data-required="sex=female" data-file="belt/buckles_female_no_th-sh/silver.png">
                                <label for="buckle-silver">Silver Buckle <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="buckle-gold" name="buckle" data-required="sex=female" data-file="belt/buckles_female_no_th-sh/gold.png">
                                <label for="buckle-gold">Gold Buckle <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Necklaces</span>
                        <ul>
                            <li>
                                <input type="radio" id="necklace-none" name="necklace" checked>
                                <label for="necklace-none">No Necklace</label>
                            </li>
                            <li>
                                <input type="radio" id="necklace-bronze" name="necklace" data-required="sex=female" data-file="accessories/necklaces_female_ no_th-sh/bronze.png">
                                <label for="necklace-bronze">Bronze Necklace <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="necklace-iron" name="necklace" data-required="sex=female" data-file="accessories/necklaces_female_ no_th-sh/iron.png">
                                <label for="necklace-iron">Iron Necklace <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="necklace-silver" name="necklace" data-required="sex=female" data-file="accessories/necklaces_female_ no_th-sh/silver.png">
                                <label for="necklace-silver">Silver Necklace <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="necklace-gold" name="necklace" data-required="sex=female" data-file="accessories/necklaces_female_ no_th-sh/gold.png">
                                <label for="necklace-gold">Gold Necklace <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Bracelet</span>
                        <ul>
                            <li>
                                <input type="radio" id="bracelet-none" name="bracelet" checked>
                                <label for="bracelet-none">No Bracelet</label>
                            </li>
                            <li>
                                <input type="radio" id="bracelet-on" name="bracelet" data-file="hands/bracelets/bracelet.png">
                                <label for="bracelet-on">Bracelet</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Cape</span>
                        <ul>
                            <li>
                                <input type="radio" id="cape-none" name="cape" checked>
                                <label for="cape-none">No Cape</label>
                            </li>
                            <li><span class="condensed">Solid Cape <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="cape_black" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_black.png" data-file_behind="behind_body/cape/normal/female/cape_black.png">
                                        <label for="cape_black">Black</small></label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-blue" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_blue.png" data-file_behind="behind_body/cape/normal/female/cape_blue.png">
                                        <label for="cape-blue">Blue</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape_brown" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_brown.png" data-file_behind="behind_body/cape/normal/female/cape_brown.png">
                                        <label for="cape_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape_gray" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_gray.png" data-file_behind="behind_body/cape/normal/female/cape_gray.png">
                                        <label for="cape_gray">Gray</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-green" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_green.png" data-file_behind="behind_body/cape/normal/female/cape_green.png">
                                        <label for="cape-green">Green</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-lavender" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_lavender.png" data-file_behind="behind_body/cape/normal/female/cape_lavender.png">
                                        <label for="cape-lavender">Lavender</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-maroon" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_maroon.png" data-file_behind="behind_body/cape/normal/female/cape_maroon.png">
                                        <label for="cape-maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-pink" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_pink.png" data-file_behind="behind_body/cape/normal/female/cape_pink.png">
                                        <label for="cape-pink">Pink</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-red" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_red.png" data-file_behind="behind_body/cape/normal/female/cape_red.png">
                                        <label for="cape-white">Red</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-white" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_white.png" data-file_behind="behind_body/cape/normal/female/cape_white.png">
                                        <label for="cape-white">White</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-yellow" name="cape" data-required="sex=female" data-file="torso/back/cape/normal/female/cape_yellow.png" data-file_behind="behind_body/cape/normal/female/cape_yellow.png">
                                        <label for="cape-yellow">Yellow</label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <input type="radio" id="cape-trimmed_white_blue" name="cape" data-required="sex=female" data-file="torso/back/cape/trimmed/female/trimcape_whiteblue.png" data-file_behind="behind_body/cape/normal/female/cape_white.png">
                                <label for="cape-trimmed_white_blue">White Cape With Blue Trim <small>(Female only)</small></label>
                            </li>
                            <li><span class="condensed">Tattered Cape <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="cape-tattered_black" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_black.png" data-file_behind="behind_body/cape/tattered/female/tattercape_black.png">
                                        <label for="cape-tattered_black">Black</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_blue" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_blue.png" data-file_behind="behind_body/cape/tattered/female/tattercape_blue.png">
                                        <label for="cape-tattered_blue">Blue</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_brown" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_brown.png" data-file_behind="behind_body/cape/tattered/female/tattercape_brown.png">
                                        <label for="cape-tattered_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_gray" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_gray.png" data-file_behind="behind_body/cape/tattered/female/tattercape_gray.png">
                                        <label for="cape-tattered_gray">Gray</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_green" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_green.png" data-file_behind="behind_body/cape/tattered/female/tattercape_green.png">
                                        <label for="cape-tattered_green">Green</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_maroon" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_maroon.png" data-file_behind="behind_body/cape/tattered/female/tattercape_maroon.png">
                                        <label for="cape-tattered_maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_pink" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_pink.png" data-file_behind="behind_body/cape/tattered/female/tattercape_pink.png">
                                        <label for="cape-tattered_pink">Pink</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_red" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_red.png" data-file_behind="behind_body/cape/tattered/female/tattercape_red.png">
                                        <label for="cape-tattered_pink">Red</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_white" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_white.png" data-file_behind="behind_body/cape/tattered/female/tattercape_white.png">
                                        <label for="cape-tattered_white">White</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="cape-tattered_yellow" name="cape" data-required="sex=female" data-file="torso/back/cape/tattered/female/tattercape_yellow.png" data-file_behind="behind_body/cape/tattered/female/tattercape_yellow.png">
                                        <label for="cape-tattered_yellow">Yellow</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Cape Clip / Tie</span>
                        <ul>
                            <li>
                                <input type="radio" id="capeacc-none" name="capeacc" checked>
                                <label for="capeacc-none">No Cape Clip / Tie</label>
                            </li>
                            <li><span class="condensed">Cape Clip <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="capeacc-clip_black" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_black.png">
                                        <label for="capeacc-clip_black">Black</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_blue" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_blue.png">
                                        <label for="capeacc-clip_blue">Blue</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_brown" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_brown.png">
                                        <label for="capeacc-clip_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_gray" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_gray.png">
                                        <label for="capeacc-clip_gray">Gray</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_green" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_green.png">
                                        <label for="capeacc-clip_green">Green</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_lavender" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_lavender.png">
                                        <label for="capeacc-clip_lavender">Lavender</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_maroon" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_maroon.png">
                                        <label for="capeacc-clip_maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_pink" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_pink.png">
                                        <label for="capeacc-clip_pink">Pink</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_red" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_red.png">
                                        <label for="capeacc-clip_pink">Red</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_white" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_white.png">
                                        <label for="capeacc-clip_white">White</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-clip_yellow" name="capeacc" data-required="sex=female" data-file="accessories/neck/capeclip/female/capeclip_yellow.png">
                                        <label for="capeacc-clip_yellow">Yellow</label>
                                    </li>
                                </ul>
                            </li>
                            <li><span class="condensed">Cape Tie <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="capeacc-tie_black" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_black.png">
                                        <label for="capeacc-tie_black">Black</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_blue" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_blue.png">
                                        <label for="capeacc-tie_blue">Blue</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_brown" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_brown.png">
                                        <label for="capeacc-tie_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_gray" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_gray.png">
                                        <label for="capeacc-tie_gray">Gray</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_green" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_green.png">
                                        <label for="capeacc-tie_green">Green</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_lavender" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_lavender.png">
                                        <label for="capeacc-tie_lavender">Lavender</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_maroon" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_maroon.png">
                                        <label for="capeacc-tie_maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_pink" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_pink.png">
                                        <label for="capeacc-tie_pink">Pink</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_red" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_red.png">
                                        <label for="capeacc-tie_pink">Red</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_white" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_white.png">
                                        <label for="capeacc-tie_white">White</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="capeacc-tie_yellow" name="capeacc" data-required="sex=female" data-file="accessories/neck/capetie/female/capetie_yellow.png">
                                        <label for="capeacc-tie_yellow">Yellow</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Weapon</span>
                        <ul>
                            <li>
                                <input type="radio" id="weapon-none" name="weapon" checked>
                                <label for="weapon-none">No Weapon</label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-bow" name="weapon" data-file="weapons/right hand/either/bow.png" data-prohibited="body=skeleton" data-preview_row="17">
                                <label for="weapon-bow">Bow</label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-bow_skeleton" name="weapon" data-file="weapons/right hand/either/bow_skeleton.png" data-required="body=skeleton" data-preview_row="17">
                                <label for="weapon-bow_skeleton">Bow <small>(for Skeleton)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-dagger" name="weapon" data-file_male="weapons/right hand/male/dagger_male.png" data-file_female="weapons/right hand/female/dagger_female.png">
                                <label for="weapon-dagger">Dagger</label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-greatbow" name="weapon" data-file="weapons/right hand/either/greatbow.png" data-preview_row="17">
                                <label for="weapon-greatbow">Great Bow</label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-recurvebow" name="weapon" data-file="weapons/right hand/either/recurvebow.png" data-preview_row="17">
                                <label for="weapon-recurvebow">Recurve Bow</label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-spear" name="weapon" data-file_male="weapons/right hand/male/spear_male.png" data-file_female="weapons/right hand/female/spear_female.png">
                                <label for="weapon-spear">Spear</label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-wand_wood" name="weapon" data-file_male="weapons/right hand/male/woodwand_male.png" data-file_female="weapons/right hand/female/woodwand_female.png" data-preview_row="13">
                                <label for="weapon-wand_wood">Wood Wand</label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-wand_steel" name="weapon" data-file="weapons/right hand/female/steelwand_female.png" data-required="sex=female" data-preview_row="13">
                                <label for="weapon-wand_steel">Steel Wand <small>(Female Only)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-largespear" name="weapon" data-file="weapons/oversize/two hand/either/spear.png" data-oversize="1">
                                <label for="weapon-largespear">Large Spear <small>(Oversize)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-dragonspear" name="weapon" data-file="weapons/oversize/two hand/either/dragonspear.png" data-oversize="1">
                                <label for="weapon-dragonspear">Dragon Spear <small>(Oversize)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-trident" name="weapon" data-file="weapons/oversize/two hand/either/trident.png" data-oversize="1">
                                <label for="weapon-trident">Trident <small>(Oversize)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-longsword" name="weapon" data-file_male="weapons/oversize/right hand/male/longsword_male.png" data-file_female="weapons/oversize/right hand/female/longsword_female.png" data-oversize="2">
                                <label for="weapon-longsword">Long Sword <small>(Oversize)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-mace" name="weapon" data-file_male="weapons/oversize/right hand/male/mace_male.png" data-file_female="weapons/oversize/right hand/female/mace_female.png" data-oversize="2">
                                <label for="weapon-mace">Mace <small>(Oversize)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-rapier" name="weapon" data-file_male="weapons/oversize/right hand/male/rapier_male.png" data-file_female="weapons/oversize/right hand/female/rapier_female.png" data-oversize="2">
                                <label for="weapon-rapier">Rapier <small>(Oversize)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="weapon-saber" name="weapon" data-file_male="weapons/oversize/right hand/male/saber_male.png" data-file_female="weapons/oversize/right hand/female/saber_female.png" data-oversize="2">
                                <label for="weapon-saber">Saber <small>(Oversize)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Ammo</span>
                        <ul>
                            <li>
                                <input type="radio" id="ammo-none" name="ammo" checked>
                                <label for="ammo-none">No Ammo</label>
                            </li>
                            <li>
                                <input type="radio" id="ammo-arrow" name="ammo" data-file="weapons/left hand/either/arrow.png" data-prohibited="body=skeleton" data-preview_row="17">
                                <label for="ammo-arrow">Arrow</label>
                            </li>
                            <li>
                                <input type="radio" id="ammo-arrow_skeleton" name="ammo" data-file="weapons/left hand/either/arrow_skeleton.png" data-required="body=skeleton" data-preview_row="17">
                                <label for="ammo-arrow_skeleton">Arrow <small>(for Skeleton)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Shield</span>
                        <ul>
                            <li>
                                <input type="radio" id="shield-none" name="shield" checked>
                                <label for="shield-none">No Shield</label>
                            </li>
                            <li>
                                <input type="radio" id="shield-on" name="shield" data-file_no_hat="weapons/left hand/male/shield_male_cutoutforbody.png" data-file_hat="weapons/left hand/male/shield_male_cutoutforhat.png" data-required="sex=male">
                                <label for="shield-on">Shield <small>(Male only)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Quiver</span>
                        <ul>
                            <li>
                                <input type="radio" id="quiver-none" name="quiver" checked>
                                <label for="quiver-none">No Quiver</label>
                            </li>
                            <li>
                                <input type="radio" id="quiver-on" name="quiver" data-file="behind_body/equipment/quiver.png" data-behind="true">
                                <label for="quiver-on">Quiver</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Hair</span>
                        <ul>
                            <li>
                                <input type="radio" id="hair-none" name="hair" checked>
                                <label for="hair-none">Bald</label>
                            </li>
                            <?php if(!empty($parts->hair->styles)): ?>
                                <?php foreach($parts->hair->styles as $style): ?>
                                    <?php $short = (!empty($style->short) ? $style->short : str_replace(' ', '', strtolower($style->name))); ?>
                                    <li>
                                        <?php if((!empty($style->colors) && $style->colors == 'none') || empty($parts->hair->colors)): ?>
                                            <?php if(!empty($style->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                    <input type="radio" id="hair-<?php echo $short; ?>" name="hair"<?php echo (!empty($style->sex) ? ' data-required="sex=' . $style->sex . '"' : ''); ?>>
                                                    <label for="hair-<?php echo $short; ?>"><?php echo $style->name; ?><?php echo (!empty($style->sex) ? ' <small>(' . ucfirst($style->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($style->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($style->opts as $opt => $val): ?>
                                                        <?php
                                                            $file    = (!empty($val->file) ? $val->file : $val->short);
                                                            $sex     = ' data-file_male="hair/male/' . $file . '.png" data-file_female="hair/female/' . $val->file . '.png"';
                                                            if(!empty($style->sex)) {
                                                                $sex = ' data-file="hair/' . $style->sex . '/' . $file . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="hair<?php echo $short; ?>-<?php echo $val->short; ?>" name="hair<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="hair=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="hair<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="condensed"><?php echo $style->name; ?><?php echo (!empty($style->sex) ? ' <small>(' . ucfirst($style->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($parts->hair->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                        $sex      = ' data-file_male="hair/male/' . $short . '/' . $slug . '.png" data-file_female="hair/female/' . $short . '/' . $slug . '.png"';
                                                        if(!empty($style->sex)) {
                                                            $sex  = ' data-file="hair/' . $style->sex . '/' . $short . '/' . $slug . '.png"';
                                                            $sex .= ' data-required="sex=' . $style->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="hair-<?php echo $short; ?>_<?php echo $slug; ?>" name="hair"<?php echo $sex; ?>>
                                                        <label for="hair-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Ears</span>
                        <ul>
                            <li>
                                <input type="radio" id="ears-none" name="ears" checked>
                                <label for="ears-none">Default</label>
                            </li>
                            <?php if(!empty($parts->body->ears)): ?>
                                <?php foreach($parts->body->ears as $ear): ?>
                                    <?php
                                        $short = (!empty($ear->short) ? $ear->short : str_replace(' ', '', strtolower($ear->name)));
                                        $colors = '';
                                        $prohibition = '';
                                        foreach($ear->prohibited as $color) {
                                            if(!empty($prohibition)) {
                                                $prohibition .= ',';
                                            }
                                            $prohibition .= 'body=' . $color;
                                        }
                                        foreach($parts->body->colors as $color) {
                                            $body = (!empty($color->short) ? $color->short : str_replace(' ', '', strtolower($color->name)));
                                            if(in_array($body, $ear->prohibited)) {
                                                continue;
                                            }
                                            $colors .= ' data-file_male_' . $body . '="body/male/ears/' . $short . '_' . $body . '.png" data-file_female_' . $body . '="body/female/ears/' . $short . '_' . $body . '.png"';
                                        }
                                        if(!empty($prohibition)) {
                                            $prohibition = ' data-prohibited="' . $prohibition . '"';
                                        }
                                    ?>
                                    <li>
                                        <input type="radio" id="ears-<?php echo $short; ?>" name="ears"<?php echo $prohibition . $colors; ?>>
                                        <label for="ears-<?php echo $short; ?>"><?php echo $ear->name; ?></label>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><span class="condensed">Hats</span>
                        <ul>
                            <li>
                                <input type="radio" id="hat-none" name="hat" checked>
                                <label for="hat-none">No Hat</label>
                            </li>
                            <li>
                                <input type="radio" id="hat-bandana_red" name="hat" data-file_male="head/bandanas/male/red.png" data-file_female="head/bandanas/female/red.png">
                                <label for="hat-bandana_red">Red Bandana</label>
                            </li>
                            <li>
                                <input type="radio" id="hat-cap_leather" name="hat" data-file_male="head/caps/male/leather_cap_male.png" data-file_female="head/caps/female/leather_cap_female.png">
                                <label for="hat-cap_leather">Leather Cap</label>
                            </li>
                            <li>
                                <input type="radio" id="hat-chain" name="hat" data-file_male="head/helms/male/chainhat_male.png" data-file_female="head/helms/female/chainhat_female.png">
                                <label for="hat-chain">Chain Hat</label>
                            </li>
                            <li>
                                <input type="radio" id="hat-hood_cloth" name="hat" data-file_male="head/hoods/male/cloth_hood_male.png" data-file_female="head/hoods/female/cloth_hood_female.png">
                                <label for="hat-hood_cloth">Cloth Hood</label>
                            </li>
                            <li>
                                <input type="radio" id="hat-hood_chain" name="hat" data-file_male="head/hoods/male/chain_hood_male.png" data-file_female="head/hoods/female/chain_hood_female.png">
                                <label for="hat-hood_chain">Chain Hood</label>
                            </li>
                            <li>
                                <input type="radio" id="hat-helmet_metal" name="hat" data-file_male="head/helms/male/metal_helm_male.png" data-file_female="head/helms/female/metal_helm_female.png">
                                <label for="hat-helmet_metal">Metal Helmet</label>
                            </li>
                            <li>
                                <input type="radio" id="hat-helmet_golden" name="hat" data-file_male="head/helms/male/golden_helm_male.png" data-file_female="head/helms/female/golden_helm_female.png">
                                <label for="hat-helmet_golden">Golden Helmet</label>
                            </li>
                            <li>
                                <span class="condensed">Tiara <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="hat-tiara_bronze" name="hat" data-required="sex=female" data-file="head/tiaras_female/bronze.png">
                                        <label for="hat-tiara_bronze">Bronze</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="hat-tiara_iron" name="hat" data-required="sex=female" data-file="head/tiaras_female/iron.png">
                                        <label for="hat-tiara_iron">Iron</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="hat-tiara_silver" name="hat" data-required="sex=female" data-file="head/tiaras_female/silver.png">
                                        <label for="hat-tiara_silver">Silver</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="hat-tiara_gold" name="hat" data-required="sex=female" data-file="head/tiaras_female/gold.png">
                                        <label for="hat-tiara_gold">Gold</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="hats-tiara_purple" name="hat" data-required="sex=female" data-file="head/tiaras_female/purple.png">
                                        <label for="hat-tiara_purple">Purple</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <p class="buttons">
                    <input type="button" id="collapse" value="Collapse All">
                    <input type="reset" value="Reset">
                </p>
            </section>
        </form>
        <section id="preview">
            <p class="instr">The complete resulting sprite sheet for your character:</p>
            <canvas id="spritesheet" width="832" height="1344">HTML5 Browser required.</canvas>
        </section>
        <p id="source"><a href="https://github.com/Gaurav0/Universal-LPC-Spritesheet-Character-Generator/commits/master">Source Code</a> &nbsp; <a href="LICENSE">LICENSE</a> &nbsp; <a href="Universal-LPC-spritesheet/AUTHORS.txt">Credits</a></p>
        <p id="save"><input type="button" id="saveAsPNG" value="Save Result As PNG"> Note: You may have to change the extension to png.</p>
    </body>
</html>