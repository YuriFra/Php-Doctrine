CREATE TABLE students (
                          id INT NOT NULL AUTO_INCREMENT,
                          first_name varchar(100) NOT NULL,
                          last_name varchar(100) NOT NULL,
                          email varchar(100) NOT NULL,
                          address_street varchar(100) NOT NULL,
                          address_streetnumber INT NOT NULL,
                          address_city VARCHAR(100) NOT NULL,
                          address_zipcode INT(4) NOT NULL,
                          PRIMARY KEY (id)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    COLLATE=utf8_general_ci;