<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/
?>
<div id="player" data-plyr-provider="<?php echo $type; ?>" data-plyr-embed-id="<?php echo $url; ?>"></div>
<script>
    const player = new Plyr('#player', {});
</script>
