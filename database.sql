--
-- Create database `ci_mahasiswa`
--

create database ci_mahasiswa;

create table mahasiswa
( nim int not null primary key,
 nama varchar(64) not null,
alamat varchar(100) not null );