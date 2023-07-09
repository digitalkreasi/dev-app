import os
from ftplib import FTP

# Informasi login FTP
ftp_server = "ftp.indofazz.com"
ftp_username = "admin@digitalkreasigroup.com"
ftp_password = "D3laszband1234."

# Direktori lokal
local_directory = "./"

# Fungsi untuk melakukan pull dari server FTP
def ftp_pull():
    ftp = FTP(ftp_server)
    ftp.login(user=ftp_username, passwd=ftp_password)

    # Pindah ke direktori yang diinginkan di server FTP
    ftp.cwd("/public_html/digitalkreasigroup.com/indofazz/")

    # Daftar file dan direktori di direktori server FTP
    file_list = ftp.nlst()

    # Pull setiap file dari server FTP ke direktori lokal
    for file_name in file_list:
        try:
            # Skip directories
            if ftp.nlst(file_name) != ['-']:
                print(f"Skipping directory: {file_name}")
                continue
        except:
            # Error checking file existence, continue with retrieval
            pass

        local_path = os.path.join(local_directory, file_name)
        with open(local_path, "wb") as local_file:
            ftp.retrbinary("RETR " + file_name, local_file.write)

    ftp.quit()

# Jalankan fungsi pull
ftp_pull()
