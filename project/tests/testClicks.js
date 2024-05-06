const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto('http://localhost/path/to/your/project/home.php'); // Настройте URL

  // Находим все кликабельные элементы
  const clickableSelectors = ['a', 'button', 'input[type=submit]', 'input[type=button]'];
  for (const selector of clickableSelectors) {
    const elements = await page.$$(selector);
    for (const element of elements) {
      await element.click();
      // По желанию можно добавить паузу или делать скриншоты
      await page.waitForTimeout(1000); // Пауза 1 секунда
    }
  }

  await browser.close();
})();
