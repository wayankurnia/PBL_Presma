CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50),
  password VARCHAR(255),
  user_type ENUM('admin', 'user','superadmin','dosen') );

  CREATE TABLE prestasi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  program_studi ENUM('TI', 'SIB', 'PPLS') NOT NULL,
  dosen VARCHAR(255) NOT NULL,
  peran_pembimbing VARCHAR(255) NOT NULL,
  peran ENUM('Ketua', 'Anggota', 'Personal') NOT NULL,
  jenis_kompetisi VARCHAR(255) NOT NULL,
  tingkat_kompetisi ENUM('kabupaten', 'provinsi', 'nasional', 'internasional') NOT NULL,
  nama_kompetisi VARCHAR(255) NOT NULL,
  juara VARCHAR(255) NOT NULL,
  tanggal_mulai DATE NOT NULL,
  tanggal_selesai DATE NOT NULL,
  foto_kegiatan VARCHAR(255) NOT NULL,
  tahun YEAR GENERATED ALWAYS AS (YEAR(tanggal_mulai)) STORED, -- Derived from tanggal_mulai
);
CREATE TABLE mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    nama VARCHAR(25),
    nim VARCHAR(20),
    prodi VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE dosen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    nama VARCHAR(25),
    nidn VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    nama VARCHAR(25),
    nip VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
INSERT INTO mahasiswa (user_id, nama, nim, prodi) VALUES
    (1, 'Syaqira Nazaretna', '2341760123', 'Sistem Informasi Bisnis'),  
    (2, 'Eksa Putra', '2341760124', 'Teknik Informatika'),              
    (3, 'Devita', '2341760125', 'Pengembangan Piranti Lunak');     
       
    ALTER TABLE prestasi ADD COLUMN user_id INT NOT NULL;
    ALTER TABLE prestasi ADD COLUMN status ENUM('proses', 'verified', 'rejected') NOT NULL DEFAULT 'proses';