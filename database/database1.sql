-- Active: 1769521603640@@localhost@5432@linkup

ALTER TABLE users ADD COLUMN username VARCHAR(100) NOT NULL AFTER name;




  SELECT * FROM users;
    
    UPDATE users SET role = 'admin' WHERE email = 'abdo.el.kabli12@gmail.com';