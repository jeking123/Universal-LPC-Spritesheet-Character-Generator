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
                            <li>
                                <input type="radio" id="body-light" name="body" checked data-file_female="body/female/light.png" data-file_male="body/male/light.png" data-hs_plain_male="hair/male/plain/shadows-lightbody.png" data-hs_ponytail2_female="hair/female/ponytail2/shadows-lightbody.png">
                                <label for="body-light">Light</label>
                            </li>
                            <li>
                                <input type="radio" id="body-dark" name="body" data-file_female="body/female/dark.png" data-file_male="body/male/dark.png" data-hs_plain_male="hair/male/plain/shadows-darkbody.png" data-hs_ponytail2_female="hair/female/ponytail2/shadows-darkbody.png">
                                <label for="body-dark">Dark</label>
                            </li>
                            <li>
                                <input type="radio" id="body-dark2" name="body" data-file_female="body/female/dark2.png" data-file_male="body/male/dark2.png" data-hs_ponytail2_female="hair/female/ponytail2/shadows-dark2body.png">
                                <label for="body-dark2">Dark 2</label>
                            </li>
                            <li>
                                <input type="radio" id="body-darkelf" name="body" data-file_female="body/female/darkelf.png" data-file_male="body/male/darkelf.png" data-hs_plain_male="hair/male/plain/shadows-darkelfbody.png" data-hs_ponytail2_female="hair/female/ponytail2/shadows-darkelfbody.png">
                                <label for="body-darkelf">Dark Elf</label>
                            </li>
                            <li>
                                <input type="radio" id="body-darkelf2" name="body" data-file_female="body/female/darkelf2.png" data-file_male="body/male/darkelf2.png" data-hs_plain_male="hair/male/plain/shadows-darkelf2body.png" data-hs_ponytail2_female="hair/female/ponytail2/shadows-darkelf2body.png">
                                <label for="body-darkelf2">Dark Elf 2</label>
                            </li>
                            <li>
                                <input type="radio" id="body-tanned" name="body" data-file_female="body/female/tanned.png" data-file_male="body/male/tanned.png" data-hs_plain_male="hair/male/plain/shadows-tannedbody.png" data-hs_ponytail2_female="hair/female/ponytail2/shadows-tannedbody.png">
                                <label for="body-tanned">Tanned</label>
                            </li>
                            <li>
                                <input type="radio" id="body-tanned2" name="body" data-file_female="body/female/tanned2.png" data-file_male="body/male/tanned2.png" data-hs_plain_male="hair/male/plain/shadows-tanned2body.png" data-hs_ponytail2_female="hair/female/ponytail2/shadows-tanned2body.png">
                                <label for="body-tanned2">Tanned 2</label>
                            </li>
                            <li>
                                <input type="radio" id="body-orc" name="body" data-file_female="body/female/orc.png" data-file_male="body/male/orc.png">
                                <label for="body-orc">Orc</label>
                            </li>
                            <li>
                                <input type="radio" id="body-orc_red" name="body" data-file_female="body/female/red_orc.png" data-file_male="body/male/red_orc.png">
                                <label for="body-orc_red">Red Orc</label>
                            </li>
                            <li>
                                <input type="radio" id="body-skeleton" name="body" data-required="sex=male" data-file="body/male/skeleton.png">
                                <label for="body-skeleton">Skeleton <small>(Male only)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Eyes</span>
                        <ul>
                            <li>
                                <input type="radio" id="eyes-none" name="eyes" checked>
                                <label for="eyes-none">Default</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes-blue" name="eyes" data-file_female="body/female/eyes/blue.png" data-file_male="body/male/eyes/blue.png">
                                <label for="eyes-blue">Blue</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes_brown" name="eyes" data-file_female="body/female/eyes/brown.png" data-file_male="body/male/eyes/brown.png">
                                <label for="eyes_brown">Brown</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes_gray" name="eyes" data-file_female="body/female/eyes/gray.png" data-file_male="body/male/eyes/gray.png">
                                <label for="eyes_gray">Gray</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes-green" name="eyes" data-file_female="body/female/eyes/green.png" data-file_male="body/male/eyes/green.png">
                                <label for="eyes-green">Green</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes-purple" name="eyes" data-file_female="body/female/eyes/purple.png" data-file_male="body/male/eyes/purple.png">
                                <label for="eyes-purple">Purple</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes-red" name="eyes" data-file_female="body/female/eyes/red.png" data-file_male="body/male/eyes/red.png">
                                <label for="eyes-red">Red</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes-yellow" name="eyes" data-file_female="body/female/eyes/yellow.png" data-file_male="body/male/eyes/yellow.png">
                                <label for="eyes-yellow">Yellow</label>
                            </li>
                            <li>
                                <input type="radio" id="eyes-orange" name="eyes" data-file_female="body/female/eyes/orange.png" data-file_male="body/male/eyes/orange.png">
                                <label for="eyes-orange">Orange</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Nose</span>
                        <ul>
                            <li>
                                <input type="radio" id="nose-none" name="nose" checked>
                                <label for="nose-none">Default</label>
                            </li>
                            <li>
                                <input type="radio" id="nose-big" name="nose" data-prohibited="body=orc,body=orc_red,body=skeleton" data-file_male_light="body/male/nose/bignose_light.png" data-file_female_light="body/female/nose/bignose_light.png" data-file_male_dark="body/male/nose/bignose_dark.png" data-file_female_dark="body/female/nose/bignose_dark.png" data-file_male_dark2="body/male/nose/bignose_dark2.png" data-file_female_dark2="body/female/nose/bignose_dark2.png" data-file_male_darkelf="body/male/nose/bignose_darkelf.png" data-file_female_darkelf="body/female/nose/bignose_darkelf.png" data-file_male_darkelf2="body/male/nose/bignose_darkelf2.png" data-file_female_darkelf2="body/female/nose/bignose_darkelf2.png" data-file_male_tanned="body/male/nose/bignose_tanned.png" data-file_female_tanned="body/female/nose/bignose_tanned.png" data-file_male_tanned2="body/male/nose/bignose_tanned2.png" data-file_female_tanned2="body/female/nose/bignose_tanned2.png">
                                <label for="nose-big">Big Nose</label>
                            </li>
                            <li>
                                <input type="radio" id="nose-button" name="nose" data-prohibited="body=orc,body=orc_red,body=skeleton" data-file_male_light="body/male/nose/buttonnose_light.png" data-file_female_light="body/female/nose/buttonnose_light.png" data-file_male_dark="body/male/nose/buttonnose_dark.png" data-file_female_dark="body/female/nose/buttonnose_dark.png" data-file_male_dark2="body/male/nose/buttonnose_dark2.png" data-file_female_dark2="body/female/nose/buttonnose_dark2.png" data-file_male_darkelf="body/male/nose/buttonnose_darkelf.png" data-file_female_darkelf="body/female/nose/buttonnose_darkelf.png" data-file_male_darkelf2="body/male/nose/buttonnose_darkelf2.png" data-file_female_darkelf2="body/female/nose/buttonnose_darkelf2.png" data-file_male_tanned="body/male/nose/buttonnose_tanned.png" data-file_female_tanned="body/female/nose/buttonnose_tanned.png" data-file_male_tanned2="body/male/nose/buttonnose_tanned2.png" data-file_female_tanned2="body/female/nose/buttonnose_tanned2.png">
                                <label for="nose-button">Button Nose</label>
                            </li>
                            <li>
                                <input type="radio" id="nose-straight" name="nose" data-prohibited="body=orc,body=orc_red,body=skeleton" data-file_male_light="body/male/nose/straightnose_light.png" data-file_female_light="body/female/nose/straightnose_light.png" data-file_male_dark="body/male/nose/straightnose_dark.png" data-file_female_dark="body/female/nose/straightnose_dark.png" data-file_male_dark2="body/male/nose/straightnose_dark2.png" data-file_female_dark2="body/female/nose/straightnose_dark2.png" data-file_male_darkelf="body/male/nose/straightnose_darkelf.png" data-file_female_darkelf="body/female/nose/straightnose_darkelf.png" data-file_male_darkelf2="body/male/nose/straightnose_darkelf2.png" data-file_female_darkelf2="body/female/nose/straightnose_darkelf2.png" data-file_male_tanned="body/male/nose/straightnose_tanned.png" data-file_female_tanned="body/female/nose/straightnose_tanned.png" data-file_male_tanned2="body/male/nose/straightnose_tanned2.png" data-file_female_tanned2="body/female/nose/straightnose_tanned2.png">
                                <label for="nose-straight">Straight Nose</label>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span class="condensed">Legs</span>
                        <ul>
                            <li>
                                <input type="radio" id="legs-none" name="legs" checked>
                                <label for="legs-none">No Legs</label>
                            </li>
                            <li><span class="condensed">Pants</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="legs-pants_magenta" name="legs" data-file_male="legs/pants/male/magenta_pants_male.png" data-file_female="legs/pants/female/magenta_pants_female.png">
                                        <label for="legs-pants_magenta">Magenta</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="legs-pants_red" name="legs" data-file_male="legs/pants/male/red_pants_male.png" data-file_female="legs/pants/female/red_pants_female.png">
                                        <label for="legs-pants_red">Red</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="legs-pants_teal" name="legs" data-file_male="legs/pants/male/teal_pants_male.png" data-file_female="legs/pants/female/teal_pants_female.png">
                                        <label for="legs-pants_teal">Teal</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="legs-pants_white" name="legs" data-file_male="legs/pants/male/white_pants_male.png" data-file_female="legs/pants/female/white_pants_female.png">
                                        <label for="legs-pants_white">White</label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <input type="radio" id="legs-robe_skirt" name="legs" data-required="sex=male" data-file="legs/skirt/male/robe_skirt_male.png">
                                <label for="legs-robe_skirt">Robe Skirt <small>(Male only)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="legs-sara" name="legs" data-required="sex=female" data-file="legs/pants/female/SaraLeggings.png">
                                <label for="legs-sara">Sara's Leggings <small>(Female only)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Clothes</span>
                        <ul>
                            <li>
                                <input type="radio" id="clothes-none" name="clothes" checked>
                                <label for="clothes-none">No Clothes</label>
                            </li>
                            <li>
                                <span class="condensed">Long Sleeve Shirt <small>(Male only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="clothes-longsleeve_brown" name="clothes" data-required="sex=male" data-file="torso/shirts/longsleeve/male/brown_longsleeve.png">
                                        <label for="clothes-longsleeve_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-longsleeve_teal" name="clothes" data-required="sex=male" data-file="torso/shirts/longsleeve/male/teal_longsleeve.png">
                                        <label for="clothes-longsleeve_teal">Teal</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-longsleeve_maroon" name="clothes" data-required="sex=male" data-file="torso/shirts/longsleeve/male/maroon_longsleeve.png">
                                        <label for="clothes-longsleeve_maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-longsleeve_white" name="clothes" data-required="sex=male" data-file="torso/shirts/longsleeve/male/white_longsleeve.png">
                                        <label for="clothes-longsleeve_white">White</label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="condensed">Sleeveless Shirt <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="clothes-sleeveless_brown" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/brown_sleeveless.png">
                                        <label for="clothes-sleeveless_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-sleeveless_teal" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/teal_sleeveless.png">
                                        <label for="clothes-sleeveless_teal">Teal</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-sleeveless_maroon" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/maroon_sleeveless.png">
                                        <label for="clothes-sleeveless_maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-sleeveless_white" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/white_sleeveless.png">
                                        <label for="clothes-sleeveless_white">White</label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="condensed">Pirate Shirt <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="clothes-pirate_brown" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/brown_pirate.png">
                                        <label for="clothes-pirate_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-pirate_teal" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/teal_pirate.png">
                                        <label for="clothes-pirate_teal">Teal</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-pirate_maroon" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/maroon_pirate.png">
                                        <label for="clothes-pirate_maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-pirate_white" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/white_pirate.png">
                                        <label for="clothes-pirate_white">White</label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="condensed">Sleeveless Tunic <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="clothes-tunic_brown" name="clothes" data-required="sex=female" data-file="torso/tunics/female/brown_tunic.png">
                                        <label for="clothes-tunic_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-tunic_teal" name="clothes" data-required="sex=female" data-file="torso/tunics/female/teal_tunic.png">
                                        <label for="clothes-tunic_teal">Teal</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-tunic_maroon" name="clothes" data-required="sex=female" data-file="torso/tunics/female/maroon_tunic.png">
                                        <label for="clothes-tunic_maroon">Maroon</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-tunic_white" name="clothes" data-required="sex=female" data-file="torso/tunics/female/white_tunic.png">
                                        <label for="clothes-tunic_white">White</label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <input type="radio" id="clothes-sara" name="clothes" data-required="sex=female" data-file="torso/shirts/sleeveless/female/SaraShirt.png">
                                <label for="clothes-sara">Sara's Shirt <small>(Female only)</small></label>
                            </li>
                            <li>
                                <span class="condensed">
                                    <input type="radio" id="clothes-formal" name="clothes" data-required="sex=male">
                                    <label for="clothes-formal">Formal Wear <small>(Male only)</small> <small>(No Thrust/Shoot)</small></label>
                                </span>
                                <ul>
                                    <li>
                                        <input type="checkbox" id="formal-shirt" name="formal-shirt" data-required="clothes=formal" data-file="formal_male_no_th-sh/shirt.png">
                                        <label for="formal-shirt">Shirt</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="formal-pants" name="formal-pants" data-required="clothes=formal" data-file="formal_male_no_th-sh/pants.png">
                                        <label for="formal-pants">Pants</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="formal-vest" name="formal-vest" data-required="clothes=formal" data-file="formal_male_no_th-sh/vest.png">
                                        <label for="formal-vest">Vest</label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <input type="radio" id="clothes-dress_sash" name="clothes" data-required="sex=female" data-file="torso/dress_female/dress_w_sash_female.png">
                                <label for="clothes-dress_sash">Dress with Sash <small>(Female only)</small></label>
                            </li>
                            <li>
                                <span class="condensed">
                                    <input type="radio" id="clothes-gown" name="clothes" data-required="sex=female">
                                    <label for="clothes-gown">Gown <small>(Female only)</small></label>
                                </span>
                                <ul>
                                    <li>
                                        <input type="checkbox" id="gown-underdress" name="gown-underdress" data-required="clothes=gown" data-file="torso/dress_female/underdress.png">
                                        <label for="gown-underdress">Underdress</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="gown-overskirt" name="gown-overskirt" data-required="clothes=gown" data-file="torso/dress_female/overskirt.png">
                                        <label for="gown-overskirt">Overskirt</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="gown-blue-vest" name="gown-blue-vest" data-required="clothes=gown" data-file="torso/dress_female/blue_vest.png">
                                        <label for="gown-blue-vest">Blue Vest</label>
                                    </li>
                                </ul>
                            </li>
                            <li><span class="condensed">Robe <small>(Female only)</small> <small>(No Thrust/Shoot)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="clothes-robe_black" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/black.png">
                                        <label for="clothes-robe_black">Black</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_blue" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/blue.png">
                                        <label for="clothes-robe_blue">Blue</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_brown" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/brown.png">
                                        <label for="clothes-robe_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_dark_brown" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/dark brown.png">
                                        <label for="clothes-robe_dark_brown">Dark Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_dark_gray" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/dark gray.png">
                                        <label for="clothes-robe_dark_gray">Dark Gray</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_light_gray" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/light gray.png">
                                        <label for="clothes-robe_light_gray">Light Gray</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_forest_green" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/forest green.png">
                                        <label for="clothes-robe_forest_green">Forest Green</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_purple" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/purple.png">
                                        <label for="clothes-robe_purple">Purple</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_red" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/red.png">
                                        <label for="clothes-robe_red">Red</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="clothes-robe_white" name="clothes" data-required="sex=female" data-file="torso/robes_female_no_th-sh/white.png">
                                        <label for="clothes-robe_white">White</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Mail</span>
                        <ul>
                            <li>
                                <input type="radio" id="mail-none" name="mail" checked>
                                <label for="mail-none">No Mail</label>
                            </li>
                            <li>
                                <input type="radio" id="mail-chain" name="mail" data-file_male="torso/chain/mail_male.png" data-file_female="torso/chain/mail_female.png">
                                <label for="mail-chain">Chain Mail</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Armor</span>
                        <ul>
                            <li>
                                <input type="radio" id="armor-none" name="armor" checked>
                                <label for="armor-none">No Armor</label>
                            </li>
                            <li>
                                <input type="radio" id="armor-chest_gold" name="armor" data-file_male="torso/gold/chest_male.png" data-file_female="torso/gold/chest_female.png">
                                <label for="armor-chest_gold">Gold Chest</label>
                            </li>
                            <li>
                                <input type="radio" id="armor-chest_leather" name="armor" data-file_male="torso/leather/chest_male.png" data-file_female="torso/leather/chest_female.png">
                                <label for="armor-chest_leather">Leather Chest</label>
                            </li>
                            <li>
                                <input type="radio" id="armor-chest_plate" name="armor" data-file_male="torso/plate/chest_male.png" data-file_female="torso/plate/chest_female.png">
                                <label for="armor-chest_plate">Plate Chest</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Jacket</span>
                        <ul>
                            <li>
                                <input type="radio" id="jacket-none" name="jacket" checked>
                                <label for="jacket-none">No Jacket</label>
                            </li>
                            <li>
                                <input type="radio" id="jacket-tabard" name="jacket" data-file_male="torso/chain/tabard/jacket_male.png" data-file_female="torso/chain/tabard/jacket_female.png">
                                <label for="jacket-tabard">Tabard</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Tie</span>
                        <ul>
                            <li>
                                <input type="radio" id="tie-none" name="tie" checked>
                                <label for="tie-none">No Tie</label>
                            </li>
                            <li>
                                <input type="radio" id="tie-on" name="tie" data-required="sex=male" data-file="formal_male_no_th-sh/tie.png">
                                <label for="tie-on">Tie <small>(Male only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="tie-bow" name="tie" data-required="sex=male" data-file="formal_male_no_th-sh/bowtie.png">
                                <label for="tie-bow">Bow Tie <small>(Male only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                        </ul>
                    </li>
                    </li>
                    <li><span class="condensed">Arms</span>
                        <ul>
                            <li>
                                <input type="radio" id="arms-none" name="arms" checked>
                                <label for="arms-none">No Arms</label>
                            </li>
                            <li>
                                <input type="radio" id="arms-gold" name="arms" data-file_male="torso/gold/arms_male.png" data-file_female="torso/gold/arms_female.png">
                                <label for="arms-gold">Gold Arms</label>
                            </li>
                            <li>
                                <input type="radio" id="arms-plate" name="arms" data-file_male="torso/plate/arms_male.png" data-file_female="torso/plate/arms_female.png">
                                <label for="arms-plate">Plate Arms</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Shoulders</span>
                        <ul>
                            <li>
                                <input type="radio" id="shoulders-none" name="shoulders" checked>
                                <label for="shoulders-none">No Shoulders</label>
                            </li>
                            <li>
                                <input type="radio" id="shoulders-leather" name="shoulders" data-file_male="torso/leather/shoulders_male.png" data-file_female="torso/leather/shoulders_female.png">
                                <label for="shoulders-leather">Leather Shoulders</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Spikes</span>
                        <ul>
                            <li>
                                <input type="radio" id="spikes-none" name="spikes" checked>
                                <label for="spikes-none">No Spikes</label>
                            </li>
                            <li>
                                <input type="radio" id="spikes-gold" name="spikes" data-required="sex=male" data-file="torso/gold/spikes_male.png">
                                <label for="spikes-gold">Gold Spikes <small>(Male only)</small></label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Bracers</span>
                        <ul>
                            <li>
                                <input type="radio" id="bracers-none" name="bracers" checked>
                                <label for="bracers-none">No Bracers</label>
                            </li>
                            <li>
                                <input type="radio" id="bracers-cloth" name="bracers" data-required="sex=female" data-file="hands/bracers/female/cloth_bracers_female.png">
                                <label for="bracers-cloth">Cloth Bracers <small>(Female only)</small></label>
                            </li>
                            <li>
                                <input type="radio" id="bracers-leather" name="bracers" data-file_male="hands/bracers/male/leather_bracers_male.png" data-file_female="hands/bracers/female/leather_bracers_female.png">
                                <label for="bracers-leather">Leather Bracers</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Greaves</span>
                        <ul>
                            <li>
                                <input type="radio" id="greaves-none" name="greaves" checked>
                                <label for="greaves-none">No Greaves</label>
                            </li>
                            <li>
                                <input type="radio" id="greaves-metal" name="greaves" data-file_male="legs/armor/male/metal_pants_male.png" data-file_female="legs/armor/female/metal_pants_female.png">
                                <label for="greaves-metal">Metal Greaves</label>
                            </li>
                            <li>
                                <input type="radio" id="greaves-golden" name="greaves" data-file_male="legs/armor/male/golden_greaves_male.png" data-file_female="legs/armor/female/golden_greaves_female.png">
                                <label for="greaves-golden">Golden Greaves</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Gloves</span>
                        <ul>
                            <li>
                                <input type="radio" id="gloves-none" name="gloves" checked>
                                <label for="gloves-none">No Gloves</label>
                            </li>
                            <li>
                                <input type="radio" id="gloves-metal" name="gloves" data-file_male="hands/gloves/male/metal_gloves_male.png" data-file_female="hands/gloves/female/metal_gloves_female.png">
                                <label for="gloves-metal">Metal Gloves</label>
                            </li>
                            <li>
                                <input type="radio" id="gloves-golden" name="gloves" data-file_male="hands/gloves/male/golden_gloves_male.png" data-file_female="hands/gloves/female/golden_gloves_female.png">
                                <label for="gloves-golden">Golden Gloves</label>
                            </li>
                        </ul>
                    </li>
                    <li><span class="condensed">Shoes</span>
                        <ul>
                            <li>
                                <input type="radio" id="shoes-none" name="shoes" checked>
                                <label for="shoes-none">No Shoes</label>
                            </li>
                            <li>
                                <span class="condensed">Shoes</span>
                                <ul>
                                    <li>
                                        <input type="radio" id="shoes_black" name="shoes" data-file_male="feet/shoes/male/black_shoes_male.png" data-file_female="feet/shoes/female/black_shoes_female.png">
                                        <label for="shoes_black">Black Shoes</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="shoes_brown" name="shoes" data-file_male="feet/shoes/male/brown_shoes_male.png" data-file_female="feet/shoes/female/brown_shoes_female.png">
                                        <label for="shoes_brown">Brown Shoes</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="shoes-maroon" name="shoes" data-file_male="feet/shoes/male/maroon_shoes_male.png" data-file_female="feet/shoes/female/maroon_shoes_female.png">
                                        <label for="shoes-maroon">Maroon Shoes</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="shoes-sara" name="shoes" data-required="sex=female" data-file="feet/shoes/female/SaraShoes.png">
                                        <label for="shoes-sara">Sara's Shoes <small>(Female only)</small></label>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <input type="radio" id="shoes-boots_metal" name="shoes" data-file_male="feet/armor/male/metal_boots_male.png" data-file_female="feet/armor/female/metal_boots_female.png">
                                <label for="shoes-boots_metal">Metal Boots</label>
                            </li>
                            <li>
                                <input type="radio" id="shoes-boots_golden" name="shoes" data-file_male="feet/armor/male/golden_boots_male.png" data-file_female="feet/armor/female/golden_boots_female.png">
                                <label for="shoes-boots_golden">Golden Boots</label>
                            </li>
                            <li>
                                <input type="radio" id="shoes-ghillies" name="shoes" data-required="sex=female" data-file="feet/ghillies_female_no_th-sh.png">
                                <label for="shoes-ghillies">Ghillies <small>(Female only)</small> <small>(No Thrust/Shoot)</small></label>
                            </li>
                            <li>
                                <span class="condensed">Slippers <small>(Female only)</small></span>
                                <ul>
                                    <li>
                                        <input type="radio" id="shoes-slippers_black" name="shoes" data-required="sex=female" data-file="feet/slippers_female/black.png">
                                        <label for="shoes-slippers_black">Black</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="shoes-slippers_brown" name="shoes" data-required="sex=female" data-file="feet/slippers_female/brown.png">
                                        <label for="shoes-slippers_brown">Brown</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="shoes-slippers_gray" name="shoes" data-required="sex=female" data-file="feet/slippers_female/gray.png">
                                        <label for="shoes-slippers_gray">Gray</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="shoes-slippers_white" name="shoes" data-required="sex=female" data-file="feet/slippers_female/white.png">
                                        <label for="shoes-slippers_white">White Slippers</label>
                                    </li>
                                </ul>
                            </li>
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
                                <?php foreach($parts->hair->styles as $name => $style): ?>
                                    <?php $short = (!empty($style->short) ? $style->short : str_replace(' ', '', strtolower($name))); ?>
                                    <li>
                                        <?php if((!empty($style->colors) && $style->colors == 'none') || empty($parts->hair->colors)): ?>
                                            <?php if(!empty($style->opts)): ?>
                                                <span class="condensed">
                                            <?php endif; ?>
                                                    <input type="radio" id="hair-<?php echo $short; ?>" name="hair"<?php echo (!empty($style->sex) ? ' data-required="sex=' . $style->sex . '"' : ''); ?>>
                                                    <label for="hair-<?php echo $short; ?>"><?php echo $name; ?><?php echo (!empty($style->sex) ? ' <small>(' . ucfirst($style->sex) . ' only)</small>' : ''); ?></label>
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
                                            <span class="condensed"><?php echo $name; ?><?php echo (!empty($style->sex) ? ' <small>(' . ucfirst($style->sex) . ' only)</small>' : ''); ?></span>
                                            <ul>
                                                <?php foreach($parts->hair->colors as $color): ?>
                                                    <?php
                                                        $slug     = preg_replace('/_(\d)/', '$1', str_replace(' ', '_', strtolower($color)));
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