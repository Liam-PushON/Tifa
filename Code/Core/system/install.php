<?php
//Install stuff
fclose(fopen('Code/Core/system/install.lock', 'w'));
header( 'Location: /' ) ;
