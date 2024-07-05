import * as functions from "./modules/functions.js";

import.meta.glob([
  '../img/**',
]);

// Default Laravel bootstrapper, installs axios
import './bootstrap';

// Added: Actual Bootstrap JavaScript dependency
import 'bootstrap';

// Added: Popper.js dependency for popover support in Bootstrap
import '@popperjs/core';

functions.switcher();
functions.accrualBalanceInfo();
functions.openReferralTree();
functions.referralLink();
functions.updateUserData();
functions.isWebp();
functions.startMiningButton();
// functions.validateCard();
functions.changePage();
functions.openPartnersLink();
functions.preloader();
functions.detectMob();
functions.swap();
