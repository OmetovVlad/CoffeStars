import { Modal } from 'bootstrap'

function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options = {}) {
  options = {
    path: '/',
  };

  if (options.expires instanceof Date) {
    options.expires = options.expires.toUTCString();
  }

  let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

  for (let optionKey in options) {
    updatedCookie += "; " + optionKey;
    let optionValue = options[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }

  document.cookie = updatedCookie;
}

function deleteCookie(name) {
  setCookie(name, "")
}

let hideNotification = setTimeout(() => {
  if (document.querySelector('.notification')) {
    document.querySelector('.notification').classList.add('hide')
  }
}, 3000);

const tg = window.Telegram.WebApp;

let user_id = 457428210
if (window.location.href.indexOf('127.0.0.1:8000') === -1) {
  user_id = tg.initDataUnsafe.user.id
  tg.expand();
}

if (!getCookie('holdTime')) {
  setCookie('holdTime', 0);
}

function notifications($text) {
  clearTimeout(hideNotification);
  const notificationEl = document.querySelector('.notification');

  notificationEl.classList.add('hide');

  notificationEl.querySelector('.text').innerHTML = $text;

  notificationEl.classList.remove('hide');

  hideNotification = setTimeout(() => {
    notificationEl.classList.add('hide')
  }, 3000);
}

export function accrualBalanceInfo() {
  let accrualBalanceEl = document.querySelector('.accrualBalance');

  if (accrualBalanceEl) {
    accrualBalanceEl.addEventListener('click', accrualBalanceOpen);

    function accrualBalanceOpen() {
      if (accrualBalanceEl.classList.contains('open')) {
        accrualBalanceEl.classList.remove('open');
      } else {
        accrualBalanceEl.classList.add('open');
      }
    }
  }
}

export function switcher() {
  let switchItems = document.querySelectorAll('.switch > div');
  let switchContentItems = document.querySelectorAll('.switch_content > div');

  switchItems.forEach(element => element.addEventListener('click', switchClick));

  function switchClick () {
    document.querySelector('.switch_content > div.active').classList.remove('active');
    document.querySelector('.switch > div.active').classList.remove('active');

    for (let i = 0; i < switchItems.length; i++) {
      if ( switchItems[i].outerHTML === this.outerHTML ) {
        this.classList.add('active');
        switchContentItems[i].classList.add('active');
      }
    }
  }
}

export function preloader() {

  if (document.querySelector('#app')) {
    const appPage = document.querySelector('#app');

    const startMining = document.querySelector('#app .startMining');
    startMining.style.width = startMining.clientHeight + 'px';

    function getRandomIntInclusive(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min + 1)) + min; //Максимум и минимум включаются
    }

    let randomPosition = [];
    randomPosition['top'] = getRandomIntInclusive(15, (appPage.offsetHeight - 145) - startMining.clientHeight);
    randomPosition['left'] = getRandomIntInclusive(10, (appPage.offsetWidth - 10) - startMining.clientWidth);

    startMining.style.top = randomPosition['top'] + 'px';
    startMining.style.left = randomPosition['left'] + 'px';

    setTimeout(() => {
      document.querySelector('.preloader').classList.add('hide');
      if (document.querySelector('#app')) {
        document.querySelector('#app').classList.remove('hide');
      }
    }, 4500);
  }
}

export function openPartnersLink() {
  const partnerItems = document.querySelectorAll('.partner');
  partnerItems.forEach(item => item.addEventListener('click', enrollCoins));

  function enrollCoins() {
    axios({
      method: 'post',
      url: '/api/users/' + user_id,
      data: {
        _method: 'PUT',
        coin_id: 1,
        partner: this.dataset.partnerId,
        method: 'partner_reward_1',
      }
    }).then((data)=> {
      if (data.data) {
        const timer = document.querySelector('#balance span');
        timer.innerHTML = Number(timer.innerHTML) + 500;
        notifications('Начислено 500 FC!<br>Выполните целевое действие и получите от 40 000 FC до 80 000 FC!');
      } else {
        notifications('Вы уже получили награду за заявку!<br>Выполните целевое действие и получите от 40 000 FC до 80 000 FC!');
      }

    }).catch(error => {
      notifications('Что-то пошло не так. Обратитесь к администратору!');
    });
  }
}

// export function newPay() {
//   const buyItems = document.querySelectorAll('.boost');
//   buyItems.forEach(item => item.addEventListener('click', createPay));
//
//   function createPay() {
//     axios({
//       method: 'post',
//       url: '/api/pay/',
//       data: {
//         user_id: user_id,
//         boost_id: this.dataset.boostid
//       }
//     }).then((data)=> {
//       // tg.showAlert(data.data);
//       // console.log(data.data);
//       // window.location.href = data.data;
//       tg.openLink(data.data);
//     }).catch(error => {
//       notifications('Что-то пошло не так. Обратитесь к администратору!');
//     });
//   }
// }

export function updateUserData() {
  if (document.querySelector('#profile_save_data_button')) {
    document.querySelector('#profile_save_data_button').addEventListener('click', function () {
      const first_name = document.querySelector('#name1').value;
      const last_name = document.querySelector('#name2').value;
      const sur_name = document.querySelector('#name3').value;
      const address = document.querySelector('#address').value;
      const phone = document.querySelector('#phone').value;

      notifications('Данные сохранены!');

      axios({
        method: 'post',
        url: '/api/users/' + user_id,
        data: {
          _method: 'PUT',
          action: 'update',
          first_name: first_name,
          last_name: last_name,
          sur_name: sur_name,
          address: address,
          phone: phone,
        }
      });

    })
  }
}

export function referralLink() {
  if (document.querySelector('#referral_link')) {
    document.querySelector('#referral_link').onclick = function () {
      navigator.clipboard.writeText(this.querySelector('input').value).then(function () {
        notifications('Реферальная ссылка скопирована в буфер обмена');
      }, function (err) {
        console.error('Произошла ошибка при копировании текста: ', err);
      });
    }
  }
}

/* Проверка поддержки webp, добавление класса webp или no-webp для HTML */
export function isWebp() {
  // Проверка поддержки webp
  function testWebP(callback) {
    let webP = new Image();
    webP.onload = webP.onerror = function () {
      callback(webP.height == 2);
    };
    webP.src = "data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA";
  }

  // Добавление класса _webp или _no-webp для HTML
  testWebP(function (support) {
    let className = support === true ? 'webp' : 'no-webp';
    document.documentElement.classList.add(className);
  });
}

// Определение устройства
export function detectMob() {

  const isNotMobile = document.querySelector('#isNotMobile');

  const profilePage = document.querySelector('#profile');
  const appPage = document.querySelector('#app');
  const withdrawPage = document.querySelector('#withdraw');
  const headerItem = document.querySelector('#header');
  const footerItem = document.querySelector('#footer');

  const toMatch = [
    /Android/i,
    /webOS/i,
    /iPhone/i,
    /iPad/i,
    /iPod/i,
    /BlackBerry/i,
    /Windows Phone/i
  ];

  const istMobile = toMatch.some((toMatchItem) => {
    return navigator.userAgent.match(toMatchItem);
  })

  if (!istMobile) {
    headerItem.remove();
    footerItem.remove();
    profilePage.remove();
    appPage.remove();
    withdrawPage.remove();
    isNotMobile.classList.remove('hide')
  } else {
    isNotMobile.classList.add('hide')
  }
}

/* Переключение страниц */
export function changePage() {

  const allPages = document.querySelectorAll('.pageWrapper');

  const footerNMenuNavItems = document.querySelectorAll('#footer .menu > div[data-page]');
  const appPage = document.querySelector('#app');

  if (appPage) {

    footerNMenuNavItems.forEach(item => item.addEventListener('click', switchPage));

    function selectMenuItem(clickedEl) {
      const menuButtons = document.querySelectorAll('#footer .menu > div[data-page]');

      menuButtons.forEach((menuButton) => {
        menuButton.classList.remove('active');
      });

      clickedEl.classList.add('active');

      window.Telegram.WebApp.HapticFeedback.impactOccurred('medium');
    }

    function switchPage() {
      allPages.forEach(page => page.classList.add('hide'));
      document.querySelector('#' + this.dataset.page).classList.remove('hide');
      selectMenuItem(this);
    }

  }
}

/* ОТКРЫТЬ/ЗАКРЫТЬ ДЕРЕВО РЕФЕРАЛОВ */
export function openReferralTree() {
  const referralTreeLines = document.querySelectorAll('.referralTree .line .name');

  referralTreeLines.forEach(item => item.addEventListener('click', openLine));

  function openLine() {
    if (this.closest('.line').querySelector('.sub')) {
      if (this.closest('.line').querySelector('.sub').classList.contains('open')) {
        this.closest('.line').querySelector('.arrow').classList.remove('open');
        this.closest('.line').querySelector('.sub').classList.remove('open');
      } else {
        this.closest('.line').querySelector('.arrow').classList.add('open');
        this.closest('.line').querySelector('.sub').classList.add('open');
      }
    }
  }

}

/* Валидация карты */
export function validateCard() {
  let input = document.querySelector("#bank-card-input");
  let validateInform = document.querySelector(".validate.card");
  let numbers = /[0-9]/;
  let regExp = /[0-9]{4}/;

  function Moon(card_number) {
    let arr = [];
    var card_number = card_number.toString();

    if (card_number.length < 16 && card_number.length > 0) {
      validateInform.classList.remove('hide');
      document.querySelector('#profile_button').disabled = true;
      return false;
    }

    if (card_number.length === 0) {
      validateInform.classList.add('hide');
      document.querySelector('#profile_button').disabled = false;
      return true;
    }

    for (let i = 0; i < card_number.length; i++) {
      if (i % 2 === 0) {
        let m = parseInt(card_number[i]) * 2;
        if (m > 9) {
          arr.push(m - 9);
        } else {
          arr.push(m);
        }
      } else {
        let n = parseInt(card_number[i]);
        arr.push(n)
      }
    }
    let summ = arr.reduce(function (a, b) {
      return a + b;
    });

    if (Boolean(!(summ % 10))) {
      validateInform.classList.add('hide');
      document.querySelector('#profile_button').disabled = false;
    } else {
      validateInform.classList.remove('hide');
      document.querySelector('#profile_button').disabled = true;
    }

    return Boolean(!(summ % 10));
  }

  // добавляем слушатель события на инпут
  input.addEventListener("input", (ev) => {
    // не позволяем ввести ничего, кроме цифр 0-9, ограничиваем размер поля 19-ю символами
    if (ev.inputType === "insertText" && !numbers.test(ev.data) || input.value.length > 19) {
      input.value = input.value.slice(0, input.value.length - 1);
      return;
    }

    // обеспечиваем работу клавиш "backspace","delete"
    let value = input.value;
    if (ev.inputType === "deleteContentBackward" && regExp.test(value.slice(-4))) {
      input.value = input.value.slice(0, input.value.length - 1);
      return;
    }

    // добавяем пробел после 4 цифр подряд
    if (regExp.test(value.slice(-4)) && value.length < 19) {
      input.value += " ";
    }

    Moon(value.replaceAll(' ', ''));
  })
}

/* Обработка работы с кнопкой */
export function startMiningButton() {

  const App = document.querySelector('#app');
  const startMining = document.querySelector('#app .startMining');
  const BgStartMining = document.querySelector('#app #bg-active');
  const BgStartMiningDefault = document.querySelector('#app #bg');
  const timer = document.querySelector('#balance span');
  const autobotImage = document.querySelector('#app .autobot');
  const autobotSwitcher = document.querySelector('#app .switch_bot');

  if (startMining) {

    function resizeCoinEvent() {
      startMining.style.width = startMining.clientHeight + 'px';
    }

    startMining.addEventListener("touchstart", startMiningEvent);
    startMining.addEventListener("touchstart", startMiningEvent);
    startMining.addEventListener("touchmove", moveMiningEvent);
    startMining.addEventListener("touchend", stopMiningEvent);
    startMining.addEventListener("touchcancel", stopMiningEvent);

    startMining.ondragstart = () => false;

    if (autobotSwitcher) {
      autobotSwitcher.addEventListener("click", startAutobotEvent);

      const autobotSum = document.querySelector('#autobot_sum');
    }

    window.addEventListener("resize", resizeCoinEvent);

    let start = 0;
    let holdTime = 0;
    let autobot = 0;

    function startMiningEvent() {
      start = 1;
      if (!startMining.classList.contains("down")) {
        BgStartMining.classList.add('show');
        BgStartMiningDefault.classList.add('hide');
        startMining.classList.add('push');
      }
    }

    function startAutobotEvent() {
      if (autobotSwitcher.classList.contains('active')) {
        autobotSwitcher.classList.remove('active');
        startMining.classList.remove('hide');
        autobotImage.classList.add('hide');
        stopMiningEvent('end autobot');

        document.querySelector('.autobot_eyes').classList.remove('active')
      } else {
        autobot = 1;
        autobotSwitcher.classList.add('active');
        startMining.classList.add('hide');
        autobotImage.classList.remove('hide');
        startMiningEvent();

        setTimeout(() => {
          document.querySelector('.autobot_eyes').classList.add('active')
        }, 1000);
      }

    }

    function stopMiningEvent($phase = null) {
      if (!autobot) {
        start = 0;
        BgStartMining.classList.remove('show');
        BgStartMiningDefault.classList.remove('hide');
        startMining.classList.remove('push');
      }

      if (getCookie('holdTime') > 0) {
        let method = 'mining';

        if (autobot) {
          method = 'autobot';

          if ($phase === 'end autobot') {
            autobot = 0;
            start = 0;
            BgStartMining.classList.remove('show');
            BgStartMiningDefault.classList.remove('hide');
            startMining.classList.remove('push');
          }
        }

        axios({
          method: 'post',
          url: '/api/users/' + user_id,
          data: {
            _method: 'PUT',
            coin_id: 1,
            method: method,
            cache: getCookie('holdTime'),
          }
        }).then((data)=> {
          notifications(data.data);
        }).catch(error => {
          notifications('Что-то пошло не так. Откройте приложение заново!<br>Ошибка: ' + error);
          console.log(error);
        });

        deleteCookie('holdTime')
      }
    }

    function moveMiningEvent(event) {
      if (start) {
        if (!((event.touches[0].clientY >= startMining.getBoundingClientRect().top) && (event.touches[0].clientY <= startMining.getBoundingClientRect().bottom) && (event.touches[0].clientX >= startMining.getBoundingClientRect().left) && (event.touches[0].clientX <= startMining.getBoundingClientRect().right))) {
          stopMiningEvent();
        }
      }
    }

    function getRandomIntInclusive(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min + 1)) + min; //Максимум и минимум включаются
    }

    let movingTimer = 0;

    const minTimeout = 6;
    const maxTimeout = 30;
    let intervalMovingButton = getRandomIntInclusive(minTimeout, maxTimeout);

    let randomPosition = [];

    setInterval(() => {
      movingTimer++;
      if (movingTimer === intervalMovingButton) {
        movingTimer = 0;
        intervalMovingButton = getRandomIntInclusive(minTimeout, maxTimeout);

        randomPosition['top'] = getRandomIntInclusive(15, App.offsetHeight - 145 - startMining.offsetHeight);
        randomPosition['left'] = getRandomIntInclusive(10, App.offsetWidth - 10 - startMining.offsetWidth);

        startMining.style.top = randomPosition['top'] + 'px';
        startMining.style.left = randomPosition['left'] + 'px';

        stopMiningEvent();
      }

      if (start) {
        setCookie('holdTime', Number(getCookie('holdTime')) + 1)

        if (autobot) {
          autobot_sum.innerHTML = Number(autobot_sum.innerHTML) + (1 * startMining.dataset.production);

          if (Number(autobot_sum.innerHTML) >= 30000) {
            autobot_sum.innerHTML = 30000;
            document.querySelector('.switch_bot').remove();

            autobotSwitcher.classList.remove('active');
            startMining.classList.remove('hide');
            autobotImage.classList.add('hide');
            stopMiningEvent('end autobot');
          }
        }


        timer.innerHTML = Number(timer.innerHTML) + (1 * startMining.dataset.production);
        window.Telegram.WebApp.HapticFeedback.impactOccurred('medium');
      }

    }, 1600);

  }
}

export function swap() {
  const swapForm = document.querySelector('#swap-form');
  const swapNot = document.querySelector('#swap-not');
  const swapLoader = document.querySelector('.modal .loader-wrapper');
  const swapFc = document.querySelector('#swap-fc');
  const swapCommission = document.querySelector('#swap-commission');
  const swapGfc = document.querySelector('#swap-gfc');
  const swapBtn = document.querySelector('#swap-btn');
  const swapFcBalance = document.querySelector('#swap-fc-balance');

  const fcBalance = document.querySelector('.wallet .balance span');
  const gfcBalance = document.querySelector('.wallet .gfc span');

  swapFc.addEventListener('input', swapFC);
  swapFc.addEventListener('change', swapFC);

  document.querySelector('#header .wallet').addEventListener('click', checkBalance);
  document.querySelector('#swap-fc-all').addEventListener('click', swapAll);
  swapBtn.addEventListener('click', swapGo);

  function swapAll () {
    swapFc.value = swapFcBalance.innerHTML;

    if (swapFcBalance.innerHTML < 100000) {
      swapBtn.disabled = true;
      swapGfc.value = '';
      swapCommission.value = '';
    } else {
      swapBtn.disabled = false;
      let gfc = swapFcBalance.innerHTML / 10000;
      swapGfc.value = Math.floor(gfc * 0.95);
      swapCommission.value = Math.ceil(gfc * 0.05);
    }
  }

  function swapFC () {
    if (this.value < 100000) {
      swapBtn.disabled = true;
      swapGfc.value = '';
      swapCommission.value = '';
    } else {
      swapBtn.disabled = false;
      let gfc = Math.ceil(this.value) / 10000;
      swapGfc.value = Math.floor(gfc * 0.95);
      swapCommission.value = Math.ceil(gfc * 0.05);
    }
  }

  function checkBalance () {
    swapForm.classList.add('hide');
    swapNot.classList.add('hide');
    swapLoader.classList.remove('hide');

    axios({
      method: 'get',
      url: '/api/users/' + user_id,
    }).then((data)=> {
      if (data.data) {
        if (data.data.data.balance >= 100000) {
          swapForm.classList.remove('hide');
          swapNot.classList.add('hide');
          swapLoader.classList.add('hide');
          swapFcBalance.innerHTML = data.data.data.balance;
        } else {
          swapForm.classList.add('hide');
          swapNot.classList.remove('hide');
          swapLoader.classList.add('hide');
          notifications('Swap доступен от 100 000 FC!');
        }
      } else {
        notifications('Что-то пошло не так. Обратитесь к администратору!');
      }
    }).catch(error => {
      notifications('Что-то пошло не так. Обратитесь к администратору!');
    });
  }
  function swapGo () {

    swapForm.classList.add('hide');
    swapNot.classList.add('hide');
    swapLoader.classList.remove('hide');

    let swapModal = document.getElementById('swapModal');
    let swapModalInstance = Modal.getInstance(swapModal);

    axios({
      method: 'get',
      url: '/api/users/' + user_id,
    }).then((data)=> {
      if (data.data) {
        if (data.data.data.balance >= swapFc.value) {
          fcBalance.innerHTML = Number(fcBalance.innerHTML) - Math.ceil(Number(swapFc.value));
          gfcBalance.innerHTML = Number(gfcBalance.innerHTML) + Number(swapGfc.value);

          swapModalInstance.hide();
          notifications('SWAP прошел успешно!');

          axios({
            method: 'post',
            url: '/api/users/swap',
            data: {
              swapFc: Math.ceil(swapFc.value),
            }
          })
        } else {
          notifications('Не достаточно FC на балансе.');
        }
      } else {
        notifications('Что-то пошло не так. Обратитесь к администратору!');
      }
    }).catch(error => {
      notifications('Что-то пошло не так. Обратитесь к администратору!');
    });
  }

}
