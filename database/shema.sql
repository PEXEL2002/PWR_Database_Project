-- Tworzenie tabeli Category
CREATE TABLE `Category` (
    `C_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `C_Name` VARCHAR(255) NOT NULL
);

-- Tworzenie tabeli User
CREATE TABLE `User` (
    `U_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `U_name` VARCHAR(255) NOT NULL,
    `U_surname` VARCHAR(255) NOT NULL,
    `U_password` VARCHAR(255) NOT NULL,
    `U_mail` VARCHAR(255) NOT NULL,
    `U_role` BOOLEAN NOT NULL,
    `U_photo` VARCHAR(255) NOT NULL
);

-- Tworzenie tabeli Business
CREATE TABLE `Business` (
    `B_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `B_contact` VARCHAR(255) NOT NULL,
    `B_NIP` VARCHAR(20) NOT NULL,
    `B_name` VARCHAR(255) NOT NULL,
    `B_photo` VARCHAR(255) NOT NULL
);

-- Tworzenie tabeli Rent_Price
CREATE TABLE `Rent_Price` (
    `RP_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `RP_price_of_eq` DECIMAL(10,2) NOT NULL,
    `RP_category` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`RP_category`) REFERENCES `Category`(`C_id`)
);

-- Tworzenie tabeli Equipment
CREATE TABLE `Equipment` (
    `E_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `E_producer` INT UNSIGNED NOT NULL,
    `E_category` INT UNSIGNED NOT NULL,
    `E_size` FLOAT NOT NULL,
    `E_price` DECIMAL(10,2) NOT NULL,
    `E_if_rent` BOOLEAN NOT NULL,
    `E_photo` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`E_producer`) REFERENCES `Business`(`B_id`),
    FOREIGN KEY (`E_category`) REFERENCES `Category`(`C_id`)
);

-- Tworzenie tabeli Rent
CREATE TABLE `Rent` (
    `R_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `R_user_id` INT UNSIGNED NOT NULL,
    `R_date_rental` DATE NOT NULL,
    `R_date_submission` DATE NULL,
    `R_price` DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (`R_user_id`) REFERENCES `User`(`U_id`)
);

-- Tworzenie tabeli Entire_Order
CREATE TABLE `Entire_Order` (
    `EO_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `EO_rent_id` INT UNSIGNED NOT NULL,
    `EO_eq_id` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`EO_rent_id`) REFERENCES `Rent`(`R_id`),
    FOREIGN KEY (`EO_eq_id`) REFERENCES `Equipment`(`E_id`)
);

-- Tworzenie tabeli Service_Price
CREATE TABLE `Service_Price` (
    `SP_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `SP_service` VARCHAR(255) NOT NULL,
    `SP_price` DECIMAL(10,2) NOT NULL
);

-- Tworzenie tabeli Service
CREATE TABLE `Service` (
    `S_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `S_user` INT UNSIGNED NOT NULL,
    `S_price` INT UNSIGNED NOT NULL,
    `S_date_in` DATE NOT NULL COMMENT 'Data przyjęcia',
    `S_date_out` DATE NULL COMMENT 'Data wydania',
    FOREIGN KEY (`S_user`) REFERENCES `User`(`U_id`),
    FOREIGN KEY (`S_price`) REFERENCES `Service_Price`(`SP_id`)
);

-- Tworzenie tabeli News
CREATE TABLE `News` (
    `N_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `N_title` VARCHAR(255) NOT NULL,
    `N_content` TEXT NOT NULL,
    `N_creator` INT UNSIGNED NOT NULL,
    `N_photo` VARCHAR(255) NULL,
    FOREIGN KEY (`N_creator`) REFERENCES `User`(`U_id`)
);
