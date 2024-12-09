
--Tabel user 
CREATE TABLE users (
id INT IDENTITY(1,1) PRIMARY KEY,
username VARCHAR(50),
password VARCHAR(225),
user_type VARCHAR(20) NOT NULL CHECK (user_type IN ('superadmin', 'mahasiswa', 'dosen', 'admin'))
);

DROP TABLE IF EXISTS users;

SELECT * FROM users;

INSERT INTO users (username, password, user_type)
VALUES
	('superadmin', 'superadmin123', 'superadmin'),
	('mahasiswa1', 'mahasiswa123', 'mahasiswa'),
	('mahasiswa2', 'mahasiswa123', 'mahasiswa'),
	('dosen1', 'dosen123', 'dosen'),
	('admin','admin123', 'admin');

	CREATE TABLE prestasi (
    id INT PRIMARY KEY IDENTITY(1,1), -- AUTO_INCREMENT di MySQL diganti dengan IDENTITY di SQL Server
    nama VARCHAR(255) NOT NULL,
    program_studi VARCHAR(10) CHECK (program_studi IN ('TI', 'SIB', 'PPLS')), -- ENUM di MySQL diganti dengan CHECK constraint
    dosen VARCHAR(255) NOT NULL,
    peran_pembimbing VARCHAR(255) NOT NULL,
    peran VARCHAR(10) CHECK (peran IN ('Ketua', 'Anggota', 'Personal')), -- ENUM di MySQL diganti dengan CHECK constraint
    jenis_kompetisi VARCHAR(255) NOT NULL,
    tingkat_kompetisi VARCHAR(20) CHECK (tingkat_kompetisi IN ('kabupaten', 'provinsi', 'nasional', 'internasional')), -- ENUM di MySQL diganti dengan CHECK constraint
    nama_kompetisi VARCHAR(255) NOT NULL,
    juara VARCHAR(255) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    foto_kegiatan VARCHAR(255) NOT NULL,
    file_sertifikat VARCHAR(255) NOT NULL,
    file_poster VARCHAR(255) NOT NULL
);

-- Menambahkan kolom user_id
ALTER TABLE prestasi 
ADD user_id INT NOT NULL;

-- Menambahkan kolom status dengan CHECK constraint
ALTER TABLE prestasi 
ADD status VARCHAR(10) NOT NULL DEFAULT 'proses' 
CHECK (status IN ('proses', 'verified', 'rejected'));