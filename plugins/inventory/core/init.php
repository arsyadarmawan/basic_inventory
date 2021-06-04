<?php

Event::listen('rainlab.user.beforeAuthenticate', 'Inventory\Core\Listeners\User@checkUserType');
Event::listen('rainlab.user.login', 'Inventory\Core\Listeners\User@afterLogin');


Event::listen('rainlab.user.register', 'Inventory\Core\Listeners\User@handleRegisteredUser');
