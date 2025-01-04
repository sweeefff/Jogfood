import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException

# Konfigurasi WebDriver
chrome_options = Options()
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--disable-dev-shm-usage")

# Path ke ChromeDriver
service = Service("C:/WebDriver/chromedriver.exe")

# Fungsi untuk login sebagai Admin
def login_as_admin(driver, username, password):
    driver.get("http://localhost/JogfoodPBL/loginform.php")
    driver.maximize_window()
    wait = WebDriverWait(driver, 10)

    # Login Admin
    username_input = wait.until(EC.presence_of_element_located((By.ID, "username")))
    password_input = driver.find_element(By.ID, "password")
    login_button = driver.find_element(By.NAME, "login")

    username_input.clear()
    password_input.clear()
    username_input.send_keys(username)
    password_input.send_keys(password)
    login_button.click()

    return wait

# Fungsi untuk cek hasil login
def check_login_result(driver, wait, expected_message, expected_url, expected_role=None):
    try:
        wait.until(EC.presence_of_element_located((By.CLASS_NAME, "swal2-title")))
        message = driver.find_element(By.CLASS_NAME, "swal2-title").text
        assert expected_message in message
        print(f"✅ {expected_message}")
    except (TimeoutException, NoSuchElementException):
        print(f"❌ {expected_message} tidak muncul.")

    # Periksa URL untuk validasi redirect
    current_url = driver.current_url
    if expected_url in current_url:
        print(f"✅ Redirect berhasil ke {expected_url}.")
        if expected_role:
            role_element = wait.until(EC.presence_of_element_located((By.ID, "role")))
            if role_element.text.strip() == expected_role:
                print(f"✅ Role sesuai: {expected_role}.")
            else:
                print(f"❌ Role tidak sesuai.")
    else:
        print(f"❌ Redirect gagal ke {expected_url}. Halaman yang dibuka: {current_url}")

# Main function untuk menguji skenario login
def test_login_scenarios():
    # WebDriver untuk admin
    driver_admin = webdriver.Chrome(service=service, options=chrome_options)
    wait_admin = WebDriverWait(driver_admin, 10)

    # 🟢 **Skenario Login Berhasil untuk Admin**
    print("\n🟢 Menguji skenario login berhasil untuk Admin...")
    login_as_admin(driver_admin, "admin", "admin.123")
    check_login_result(driver_admin, wait_admin, "Login Berhasil", "dashboard.php", "admin")
    
    driver_admin.quit()

    # WebDriver untuk user
    driver_user = webdriver.Chrome(service=service, options=chrome_options)
    wait_user = WebDriverWait(driver_user, 10)

    # 🟢 **Skenario Login Berhasil untuk User**
    print("\n🟢 Menguji skenario login berhasil untuk User...")
    login_as_admin(driver_user, "pengguna1", "112233")  # Ganti username/password sesuai user yang valid
    check_login_result(driver_user, wait_user, "Login Berhasil", "index.php", "user")

    driver_user.quit()

    # WebDriver untuk admin login gagal
    driver_admin_fail = webdriver.Chrome(service=service, options=chrome_options)
    wait_admin_fail = WebDriverWait(driver_admin_fail, 10)

    # 🔴 **Skenario Login Gagal untuk Admin**
    print("\n🔴 Menguji skenario login gagal untuk Admin...")
    login_as_admin(driver_admin_fail, "admin", "password_salah")  # Ganti dengan password salah
    check_login_result(driver_admin_fail, wait_admin_fail, "Login Gagal", "loginform.php")

    driver_admin_fail.quit()

    # WebDriver untuk user login gagal
    driver_user_fail = webdriver.Chrome(service=service, options=chrome_options)
    wait_user_fail = WebDriverWait(driver_user_fail, 10)

    # 🔴 **Skenario Login Gagal untuk User**
    print("\n🔴 Menguji skenario login gagal untuk User...")
    login_as_admin(driver_user_fail, "pengguna1", "password_salah")  # Ganti dengan password salah
    check_login_result(driver_user_fail, wait_user_fail, "Login Gagal", "loginform.php")

    driver_user_fail.quit()

# Main execution
try:
    test_login_scenarios()
except Exception as e:
    print(f"⚠️ Terjadi kesalahan: {e}")
finally:
    print("\n🛑 Pengujian selesai.")
