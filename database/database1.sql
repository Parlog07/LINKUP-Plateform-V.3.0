-- Active: 1769521603640@@localhost@5432@linkup

ALTER TABLE users ADD COLUMN username VARCHAR(100) NOT NULL AFTER name;




  SELECT * FROM users;
    
    UPDATE users SET role = 'admin' WHERE email = 'abdo.el.kabli12@gmail.com';

    CREATE INDEX idx_messages_conversation_id ON messages(conversation_id);
CREATE INDEX idx_messages_sender_id ON messages(sender_id);
CREATE INDEX idx_messages_created_at ON messages(created_at);
CREATE INDEX idx_messages_is_read ON messages(is_read);
