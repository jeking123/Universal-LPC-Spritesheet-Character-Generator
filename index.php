<?php
    require_once('include.php');
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
                    <?php output_body(); ?>
                    <?php output_eyes(); ?>
                    <?php output_nose(); ?>
                    <?php output_general($parts->clothes->legs, 'clothes', 'Legs', 'legs', 'legs'); ?>
                    <?php output_general($parts->clothes->torso, 'clothes', 'Clothes', 'clothes', 'torso'); ?>
                    <?php output_general($parts->clothes->vests, 'clothes', 'Vest', 'vest', 'torso/vest'); ?>
                    <?php output_general($parts->armor->chest, 'armor', 'Armor', 'armor', 'torso/chain'); ?>
                    <?php output_general($parts->armor->jacket, 'armor', 'Jacket', 'jacket', 'torso/chain'); ?>
                    <?php output_general($parts->armor->arms, 'armor', 'Arms', 'arms', 'torso'); ?>
                    <?php output_general($parts->armor->shoulders, 'armor', 'Shoulders', 'shoulders', 'torso'); ?>
                    <?php output_general($parts->armor->spikes, 'armor', 'Spikes', 'shoulders', 'torso'); ?>
                    <?php output_general($parts->armor->bracers, 'armor', 'Bracers', 'bracers', 'hands/bracers'); ?>
                    <?php output_general($parts->armor->greaves, 'armor', 'Greaves', 'greaves', 'legs/armor'); ?>
                    <?php output_general($parts->armor->gloves, 'armor', 'Gloves', 'gloves', 'hands/gloves'); ?>
                    <?php output_general($parts->clothes->shoes, 'clothes', 'Shoes', 'shoes', 'feet'); ?>
                    <?php output_general($parts->accessories->belts, 'accessories', 'Belts', 'belt', 'belt'); ?>
                    <?php output_general($parts->accessories->buckles, 'accessories', 'Buckles', 'buckle', 'belt'); ?>
                    <?php output_general($parts->accessories->bracelets, 'accessories', 'Bracelet', 'bracelet', 'hands/bracelets'); ?>
                    <?php //output_general($parts->accessories->capes, 'Capes', 'cape', 'torso/back/cape', 'behind_body/cape'); ?>
                    <li><span class="condensed">Cape</span>
                        <ul>
                            <li>
                                <input type="radio" id="cape-none" name="cape" checked>
                                <label for="cape-none">No Cape</label>
                            </li>
                            <?php if(!empty($parts->accessories->capes)): ?>
                                <?php foreach($parts->accessories->capes as $cape): ?>
                                    <?php
                                        $short = (!empty($cape->short) ? $cape->short : str_replace(' ', '', strtolower($cape->name)));
                                        $path  = (!empty($cape->path) ? $cape->path : 'torso/back/cape');
                                        $back  = (!empty($cape->back) ? $cape->back : 'behind_body/cape');
                                        if(empty($cape->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        if(empty($cape->fullback)) {
                                            $back .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);

                                        $back  = str_replace('%SHORT%', $short, $back);
                                        $backm = str_replace('%SEX%', 'male', $back);
                                        $backf = str_replace('%SEX%', 'female', $back);
                                    ?>
                                    <li>
                                        <?php if((!empty($cape->colors) && $cape->colors == 'none') || (empty($parts->clothes->colors) && empty($cape->colors))): ?>
                                            <?php if(!empty($cape->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($cape->file) ? $cape->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($cape->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png" data-file_behind_male="' . $backm . '/' . $filem . '.png" data-file_behind_female="' . $pathf . '/' . $backf . '.png"';
                                                        if(!empty($cape->sex)) {
                                                            $files = str_replace('%SEX%', $cape->sex, $file);
                                                            $paths = str_replace('%SEX%', $cape->sex, $path);
                                                            $backs = str_replace('%SEX%', $cape->sex, $back);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png" data-file_behind="' . $backs . '/' . $files . '.png"';
                                                        }
                                                    }
                                                ?>
                                                    <input type="radio" id="cape-<?php echo $short; ?>" name="cape"<?php echo (!empty($cape->sex) ? ' data-required="sex=' . $cape->sex . '"' : '') . $sex; ?>>
                                                    <label for="cape-<?php echo $short; ?>"><?php echo $cape->name; ?><?php echo (!empty($cape->sex) ? ' <small>(' . ucfirst($cape->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($cape->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($cape->opts as $opt => $val): ?>
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

                                                            $back2   = (!empty($val->back) ? $val->back : $back);
                                                            $back2m  = str_replace('%SEX%', 'male', $back2);
                                                            $back2f  = str_replace('%SEX%', 'female', $back2);

                                                            $sex     = ' data-file_male="' . $path2m . '/' . $filem . '.png" data-file_female="' . $path2f . '/' . $filef . '.png" data-file_behind_male="' . $back2m . '/' . $filem . '.png" data-file_behind_female="' . $back2f . '/' . $filef . '.png"';
                                                            if(!empty($cape->sex)) {
                                                                $files = str_replace('%SEX%', $cape->sex, $file);
                                                                $paths = str_replace('%SEX%', $cape->sex, $path2);
                                                                $backs = str_replace('%SEX%', $cape->sex, $back2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png" data-file_behind="' . $backs . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="cape<?php echo $short; ?>-<?php echo $val->short; ?>" name="cape<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="cape=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="cape<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $colors = $parts->accessories->colors;
                                            if(!empty($cape->colors)) {
                                                $colors = $cape->colors;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $cape->name; ?><?php echo (!empty($cape->sex) ? ' <small>(' . ucfirst($cape->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($cape->colors as $color): ?>
                                                    <?php if(is_string($color) || (!is_string($color) && empty($color->trims))):
                                                            if(!is_string($color)) {
                                                                $color = $color->name;
                                                            }

                                                            $slug      = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color)));
                                                            $file      = (!empty($cape->file) ? $cape->file : $slug . '_' . $short . '_%SEX%');
                                                            $file      = str_replace('%COLOR%', $slug, $file);
                                                            $file      = str_replace('%SHORT%', $short, $file);
                                                            $filem     = str_replace('%SEX%', 'male', $file);
                                                            $filef     = str_replace('%SEX%', 'female', $file);

                                                            $sex       = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png" data-file_behind_male="' . $backm . '/' . $filem . '.png" data-file_behind_female="' . $backf . '/' . $filef . '.png"';
                                                            if(!empty($cape->sex)) {
                                                                $files = str_replace('%SEX%', $cape->sex, $file);
                                                                $paths = str_replace('%SEX%', $cape->sex, $path);
                                                                $backs = str_replace('%SEX%', $cape->sex, $back);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png" data-file_behind="' . $backs . '/' . $files . '.png"';
                                                                $sex  .= ' data-required="sex=' . $cape->sex . '"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="radio" id="cape-<?php echo $short; ?>_<?php echo $slug; ?>" name="cape"<?php echo $sex; ?>>
                                                            <label for="cape-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color; ?></label>
                                                        </li>
                                                    <?php else:
                                                            foreach($color->trims as $trim):
                                                                $slug      = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($color->name)));
                                                                $file      = (!empty($cape->file) ? $cape->file : $slug . '_' . $short . '_%SEX%');
                                                                $file      = str_replace('%COLOR%', $slug, $file);
                                                                $file      = str_replace('%SHORT%', $short, $file);
                                                                $file      = str_replace('%TRIM%', strtolower($trim), $file);
                                                                $filem     = str_replace('%SEX%', 'male', $file);
                                                                $filef     = str_replace('%SEX%', 'female', $file);

                                                                $behind    = (!empty($cape->behind) ? $cape->behind : $slug . '_' . $short . '_%SEX%');
                                                                $behind    = str_replace('%COLOR%', $slug, $behind);
                                                                $behind    = str_replace('%SHORT%', $short, $behind);
                                                                $behind    = str_replace('%TRIM%', $trim, $behind);
                                                                $behindm   = str_replace('%SEX%', 'male', $behind);
                                                                $behindf   = str_replace('%SEX%', 'female', $behind);

                                                                $sex       = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png" data-file_behind_male="' . $backm . '/' . $behindm . '.png" data-file_behind_female="' . $backf . '/' . $behindf . '.png"';
                                                                if(!empty($cape->sex)) {
                                                                    $files = str_replace('%SEX%', $cape->sex, $file);
                                                                    $behinds = str_replace('%SEX%', $cape->sex, $behind);
                                                                    $paths = str_replace('%SEX%', $cape->sex, $path);
                                                                    $backs = str_replace('%SEX%', $cape->sex, $back);
                                                                    $sex   = ' data-file="' . $paths . '/' . $files . '.png" data-file_behind="' . $backs . '/' . $behinds . '.png"';
                                                                    $sex  .= ' data-required="sex=' . $cape->sex . '"';
                                                                }
                                                        ?>
                                                            <li>
                                                                <input type="radio" id="cape-<?php echo $short; ?>_<?php echo $slug; ?>" name="cape"<?php echo $sex; ?>>
                                                                <label for="cape-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $color->name; ?>, <?php echo $trim; ?> Trim</label>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php output_general($parts->accessories->capeacc, 'accessories', 'Cape Neckpiece', 'capeacc', 'accessories/neck'); ?>
                    <li><span class="condensed">Weapon</span>
                        <ul>
                            <li>
                                <input type="radio" id="weapon-none" name="weapon" checked>
                                <label for="weapon-none">No Weapon</label>
                            </li>
                            <?php if(!empty($parts->equipment->weapons)): ?>
                                <?php foreach($parts->equipment->weapons as $weapon): ?>
                                    <?php
                                        $short = (!empty($weapon->short) ? $weapon->short : str_replace(' ', '', strtolower($weapon->name)));
                                        $path  = (!empty($weapon->path) ? $weapon->path : 'weapons');
                                        if(empty($weapon->fullpath)) {
                                            $path .= '/%SHORT%/%SEX%';
                                        }
                                        $path  = str_replace('%SHORT%', $short, $path);
                                        $pathm = str_replace('%SEX%', 'male', $path);
                                        $pathf = str_replace('%SEX%', 'female', $path);
                                    ?>
                                    <li>
                                        <?php if((!empty($weapon->hands) && $weapon->hands == 'none') || (empty($parts->clothes->hands) && empty($weapon->hands))): ?>
                                            <?php if(!empty($weapon->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                <?php
                                                    $file    = (!empty($weapon->file) ? $weapon->file : $short);
                                                    $file    = str_replace('%SHORT%', $short, $file);
                                                    $filem   = str_replace('%SEX%', 'male', $file);
                                                    $filef   = str_replace('%SEX%', 'female', $file);
                                                    $sex     = '';
                                                    if(empty($weapon->opts)) {
                                                        $sex     = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($weapon->sex)) {
                                                            $files = str_replace('%SEX%', $weapon->sex, $file);
                                                            $paths = str_replace('%SEX%', $weapon->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                        }
                                                    }

                                                    $preview = '';
                                                ?>
                                                    <input type="radio" id="weapon-<?php echo $short; ?>" name="weapon"<?php echo (!empty($weapon->sex) ? ' data-required="sex=' . $weapon->sex . '"' : '') . $sex; ?>>
                                                    <label for="weapon-<?php echo $short; ?>"><?php echo $weapon->name; ?><?php echo (!empty($weapon->sex) && ($weapon->sex != 'either') ? ' <small>(' . ucfirst($weapon->sex) . ' only)</small>' : ''); ?></label>
                                            <?php if(!empty($weapon->opts)): ?>
                                                </span>
                                                <ul>
                                                    <?php foreach($weapon->opts as $opt => $val): ?>
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
                                                            if(!empty($weapon->sex)) {
                                                                $files = str_replace('%SEX%', $weapon->sex, $file);
                                                                $paths = str_replace('%SEX%', $weapon->sex, $path2);
                                                                $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input type="checkbox" id="weapon<?php echo $short; ?>-<?php echo $val->short; ?>" name="weapon<?php echo $short; ?>-<?php echo $val->short; ?>" data-required="weapon=<?php echo $short; ?>"<?php echo $sex; ?> data-behind="true" data-preview_row="1">
                                                            <label for="weapon<?php echo $short; ?>-<?php echo $val->short; ?>"><?php echo $opt; ?></label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else:
                                            $hands = $parts->accessories->hands;
                                            if(!empty($weapon->hands)) {
                                                $hands = $weapon->hands;
                                            }
                                        ?>
                                            <span class="condensed"><?php echo $weapon->name; ?><?php echo (!empty($weapon->sex) ? ' <small>(' . ucfirst($weapon->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($weapon->hands as $hand): ?>
                                                    <?php
                                                        $slug     = preg_replace('/-(\d)/', '$1', str_replace(' ', '-', strtolower($hand)));
                                                        $file     = (!empty($weapon->file) ? $weapon->file : $slug . '_' . $short . '_%SEX%');
                                                        $file     = str_replace('%COLOR%', $slug, $file);
                                                        $file     = str_replace('%SHORT%', $short, $file);
                                                        $filem    = str_replace('%SEX%', 'male', $file);
                                                        $filef    = str_replace('%SEX%', 'female', $file);

                                                        $sex      = ' data-file_male="' . $pathm . '/' . $filem . '.png" data-file_female="' . $pathf . '/' . $filef . '.png"';
                                                        if(!empty($weapon->sex)) {
                                                            $files = str_replace('%SEX%', $weapon->sex, $file);
                                                            $paths = str_replace('%SEX%', $weapon->sex, $path);
                                                            $sex   = ' data-file="' . $paths . '/' . $files . '.png"';
                                                            $sex  .= ' data-required="sex=' . $weapon->sex . '"';
                                                        }
                                                    ?>
                                                    <li>
                                                        <input type="radio" id="weapon-<?php echo $short; ?>_<?php echo $slug; ?>" name="weapon"<?php echo $sex; ?>>
                                                        <label for="weapon-<?php echo $short; ?>_<?php echo $slug; ?>"><?php echo $hand; ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
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