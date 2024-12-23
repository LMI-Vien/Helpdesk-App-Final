-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 04:12 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdesk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `recid` int(11) NOT NULL,
  `dept_desc` varchar(100) NOT NULL,
  `manager_id` varchar(10) DEFAULT NULL,
  `sup_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`recid`, `dept_desc`, `manager_id`, `sup_id`) VALUES
(1, 'Information Communication Technology', '23-0001', '23-0001'),
(26, 'Finance', '2', '3'),
(27, 'Human Resources', '1', '2'),
(48, 'Accounting', '110', '110'),
(49, 'Warehouse', '2014-1', '2024-1'),
(50, 'Marketing', '121212', '1212');

-- --------------------------------------------------------

--
-- Table structure for table `filled_by_mis`
--

CREATE TABLE `filled_by_mis` (
  `recid` int(11) NOT NULL,
  `control_number` varchar(20) NOT NULL,
  `for_mis_concern` tinyint(1) NOT NULL,
  `for_lst_concern` tinyint(1) NOT NULL,
  `system_error` tinyint(1) NOT NULL,
  `user_error` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filled_by_mis`
--

INSERT INTO `filled_by_mis` (`recid`, `control_number`, `for_mis_concern`, `for_lst_concern`, `system_error`, `user_error`) VALUES
(1, 'DL-224', 1, 1, 1, 1),
(2, 'DL-225', 0, 0, 0, 0),
(3, 'DL-226', 0, 0, 0, 0),
(4, 'DL-230', 0, 0, 0, 0),
(5, 'DL-228', 0, 0, 0, 0),
(6, 'DL-229', 0, 0, 0, 0),
(7, 'DL-231', 0, 0, 0, 0),
(8, 'DL-232', 1, 0, 1, 0),
(9, 'DL-233', 0, 0, 0, 0),
(10, 'DL-234', 0, 0, 0, 0),
(11, 'DL-235', 0, 0, 0, 0),
(12, 'DL-236', 0, 0, 0, 0),
(13, 'DL-237', 0, 0, 0, 0),
(14, 'WH-2', 0, 0, 0, 0),
(15, 'WW-1', 0, 0, 0, 0),
(16, '3-A', 0, 0, 0, 0),
(17, '3-B', 0, 0, 0, 0),
(18, 'WH-1-2024', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `service_request_msrf`
--

CREATE TABLE `service_request_msrf` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `requestor_name` varchar(155) NOT NULL,
  `department` varchar(150) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `date_requested` date DEFAULT NULL,
  `date_needed` date DEFAULT NULL,
  `asset_code` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `specify` varchar(150) NOT NULL,
  `details_concern` text,
  `file` varchar(255) NOT NULL,
  `status` enum('Open','In Progress','Resolved','Closed','Rejected','Returned','Approved') DEFAULT NULL,
  `approval_status` enum('Approved','Pending','Rejected','Returned') DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT NULL,
  `requester_id` int(11) DEFAULT NULL,
  `sup_id` int(11) NOT NULL,
  `it_dept_id` int(11) DEFAULT NULL,
  `it_sup_id` varchar(10) DEFAULT NULL,
  `it_approval_status` enum('Approved','Pending','Rejected','Resolved') DEFAULT NULL,
  `assigned_it_staff` varchar(10) DEFAULT NULL,
  `remarks_ict` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_request_msrf`
--

INSERT INTO `service_request_msrf` (`recid`, `ticket_id`, `subject`, `requestor_name`, `department`, `dept_id`, `date_requested`, `date_needed`, `asset_code`, `category`, `specify`, `details_concern`, `file`, `status`, `approval_status`, `priority`, `requester_id`, `sup_id`, `it_dept_id`, `it_sup_id`, `it_approval_status`, `assigned_it_staff`, `remarks_ict`, `created_at`) VALUES
(2, 'MSRF-001', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Accounting', 2, '2024-05-22', '2024-05-22', 'LMI227-4-3-24', 'computer', '', '<p>Test ticketing!</p>', '', 'Closed', 'Approved', 'High', 1, 4, 1, '23-0001', 'Resolved', '24-0403A', NULL, '2024-05-22'),
(3, 'MSRF-002', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Accounting', 2, '0000-00-00', '2024-06-19', 'Test-0123', 'network', '', '<p>Test Tickets</p>', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', '24-0403A', '                                                                                                    ', '2024-06-19'),
(4, 'MSRF-003', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Accounting', 2, '0000-00-00', '2024-08-13', '1', 'computer', '', '', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, '                         ', '2024-08-13'),
(5, 'MSRF-004', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Accounting', 2, '0000-00-00', '2024-08-13', '2', 'others', 'ss', '<p>s</p>', '', 'Closed', 'Approved', 'Low', 1, 2, 1, '23-0001', 'Resolved', NULL, '                         ', '2024-08-13'),
(6, 'MSRF-005', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Accounting', 2, '0000-00-00', '2024-08-14', '4', 'network', '', '', '', 'Closed', 'Rejected', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, 'wwwwww', '2024-08-13'),
(7, 'MSRF-006', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Accounting', 2, '0000-00-00', '2024-08-17', '5', 'computer', '', 'namamatay matay', '', 'Closed', 'Rejected', 'High', 1, 2, 1, '23-0001', 'Resolved', 'ChristianJ', 'kase bawal yon          ', '2024-08-15'),
(8, 'MSRF-007', 'MSRF', 'Robert Vien  Santiago', 'Accounting', 2, '0000-00-00', '2024-08-23', '6', 'computer', '', '<p>bagal ram</p>', '', 'Closed', 'Pending', 'High', 8, 2, 1, '23-0001', 'Resolved', 'ChristanA', NULL, '2024-08-15'),
(9, 'MSRF-008', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Finance', 26, '0000-00-00', '2024-08-24', '6', 'projector', '', '', '', 'Closed', 'Approved', 'Medium', 1, 26, 1, '23-0001', 'Resolved', NULL, 'bawal yon', '2024-08-24'),
(10, 'MSRF-009', 'MSRF', 'Gilbert Aaron Picardo Adane', '', 2, '0000-00-00', '2024-09-07', '6', 'computer', '', '<p><img alt=\"\" src=\"https://drive.google.com/drive/u/0/my-drive\"><br></p>', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, 'wddw                        ', '2024-09-05'),
(11, 'MSRF-010', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '0000-00-00', '2024-09-07', '6', 'projector', '', '', '', 'Closed', 'Approved', 'Medium', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-09-06'),
(12, 'MSRF-011', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '0000-00-00', '2024-09-14', '222', 'computer', '', '', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-09-06'),
(13, 'MSRF-012', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-09-06', '2024-09-28', 'LMI', 'computer', '', '', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-09-06'),
(14, 'MSRF-013', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-09-07', '2024-09-07', 'ww', 'projector', '', '', '', 'Closed', 'Approved', 'Medium', 1, 2, 1, '23-0001', 'Resolved', NULL, 'bawal kase yon          ', '2024-09-07'),
(15, 'MSRF-014', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-09-07', '2024-09-07', 'aw', 'printer', '', '', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-09-07'),
(16, 'MSRF-015', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-09-12', '2024-09-14', 'LMI', 'others', 'email add', '', '', 'Closed', 'Approved', 'Low', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-09-12'),
(17, 'MSRF-016', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-02', '2024-10-10', '222', 'others', 'wa', 's', '', 'Closed', 'Approved', 'Low', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-10-02'),
(18, 'MSRF-017', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-14', '2024-10-14', '2222', 'computer', '', '21', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', 'Christian_', NULL, '2024-10-14'),
(19, 'MSRF-018', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-15', '2024-10-15', '', '', '', 'di na nabukas', '', 'Closed', 'Rejected', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-10-15'),
(20, 'MSRF-019', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-16', '2024-10-16', 'LMI 227', 'printer', '', 'pa connect po sir chinchan', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', 'ChristianJ', NULL, '2024-10-16'),
(21, 'MSRF-020', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-16', '2024-10-16', '12', 'others', 'www', 'wawits', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-10-16'),
(22, 'MSRF-021', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-17', '2024-10-17', '12', 'computer', '', 'ww', '', 'Closed', 'Approved', 'High', 1, 2, 1, '23-0001', 'Resolved', NULL, NULL, '2024-10-17'),
(23, 'MSRF-022', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-17', '2024-10-17', 'ww', 'printer', '', 'ww', '', 'In Progress', 'Approved', 'High', 1, 2, 1, '23-0001', 'Approved', '', '', '2024-10-17'),
(25, 'MSRF-023', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-17', '2024-10-17', 'ww', 'network', '', 'w', '', 'In Progress', 'Approved', 'High', 1, 2, 1, '23-0001', 'Approved', '', 'ww', '2024-10-17'),
(26, 'MSRF-024', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-17', '2024-10-17', 'www', 'printer', '', 'www', '', 'In Progress', 'Approved', 'High', 1, 2, 1, '23-0001', 'Approved', 'ChristanA', '', '2024-10-17'),
(27, 'MSRF-025', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-17', '2024-10-17', 'www', '', '', 'ww', '', 'In Progress', 'Approved', 'High', 1, 2, 1, '23-0001', 'Approved', 'ChristianJ', 'ww', '2024-10-17'),
(31, 'MSRF-026', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-10-17', '2024-10-17', '123', 'printer', '', 'www', '1729136587_Luffy.jpg', 'Approved', 'Approved', 'High', 1, 2, 1, '23-0001', 'Pending', NULL, '', '2024-10-17'),
(32, 'MSRF-027', 'MSRF', 'Louie   Louie', 'Accounting', 48, '2024-11-04', '2024-11-08', '2024-1', 'projector', '', NULL, '', 'Returned', 'Returned', 'Medium', 42, 0, 1, '23-0001', 'Pending', NULL, NULL, '2024-11-04'),
(33, 'MSRF-028', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-11-04', '2024-11-04', '2024-LMI', 'others', 'weww', '', '1730712493_Fixes-Enhancements_Release_Form_(2).pdf', 'Open', 'Pending', 'Low', 1, 2, 1, '23-0001', 'Pending', NULL, '', '2024-11-04'),
(34, 'MSRF-029', 'MSRF', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-12-12', '2025-01-11', 'LMI102', 'computer', '', NULL, '', 'Open', 'Pending', 'High', 1, 2, 1, '23-0001', 'Pending', NULL, '', '2024-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `service_request_tracc_concern`
--

CREATE TABLE `service_request_tracc_concern` (
  `recid` int(11) NOT NULL,
  `control_number` varchar(10) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `module_affected` varchar(10) NOT NULL,
  `company` enum('LMI','LPI','RGDI','SV') NOT NULL,
  `tcr_details` varchar(255) NOT NULL,
  `reported_by` varchar(150) NOT NULL,
  `reported_by_id` int(11) NOT NULL,
  `reported_date` date NOT NULL,
  `file` varchar(255) NOT NULL,
  `received_by` varchar(150) NOT NULL,
  `noted_by` varchar(150) NOT NULL,
  `tcr_solution` varchar(255) NOT NULL,
  `resolved_by` varchar(10) NOT NULL,
  `resolved_date` date NOT NULL,
  `ack_as_resolved` varchar(10) NOT NULL,
  `ack_as_resolved_date` date NOT NULL,
  `others` varchar(255) NOT NULL,
  `priority` enum('Low','Medium','High') NOT NULL,
  `status` enum('Open','Approved','In Progress','Rejected','Resolved','Closed','Done','Approved','Returned') NOT NULL,
  `approval_status` enum('Approved','Pending','Rejected','Returned') NOT NULL,
  `it_approval_status` enum('Approved','Pending','Rejected','Resolved','Closed') NOT NULL,
  `reason_reject_tickets` varchar(100) NOT NULL,
  `received_by_lst` varchar(100) NOT NULL,
  `date_lst` date NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_request_tracc_concern`
--

INSERT INTO `service_request_tracc_concern` (`recid`, `control_number`, `subject`, `module_affected`, `company`, `tcr_details`, `reported_by`, `reported_by_id`, `reported_date`, `file`, `received_by`, `noted_by`, `tcr_solution`, `resolved_by`, `resolved_date`, `ack_as_resolved`, `ack_as_resolved_date`, `others`, `priority`, `status`, `approval_status`, `it_approval_status`, `reason_reject_tickets`, `received_by_lst`, `date_lst`, `created_at`) VALUES
(3, 'DL-224', 'TRACC_CONCERN', 'Sales', 'LPI', 'we were', 'Gilbert Aaron Picardo Adane', 1, '2024-09-17', '', 'HANNA', 'CK', 'gentos', 'CK', '2024-09-26', 'GAdane', '2024-09-20', 'wawits', 'Low', 'Closed', 'Approved', 'Closed', '', 'g', '2024-10-02', '2024-09-17'),
(4, 'DL-225', 'TRACC_CONCERN', 'TRACC', 'LMI', '-\r\n-\r\n-\r\n-', 'Gilbert Aaron Picardo Adane', 1, '2024-09-18', '', '', '', '', '', '0000-00-00', 'Louise', '2024-10-04', '', 'Low', 'Closed', 'Approved', 'Closed', '', 'g', '2024-10-26', '2024-09-18'),
(5, 'DL-226', 'TRACC_CONCERN', 'TRACC', 'LMI', 'may ayaw ma edit', 'Gilbert Aaron Picardo Adane', 1, '2024-09-28', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Closed', 'Rejected', 'Closed', 'bawal e', '', '0000-00-00', '2024-09-28'),
(7, 'DL-228', 'TRACC_CONCERN', 'Sales', 'RGDI', '', 'Gilbert Aaron Picardo Adane', 1, '2024-10-03', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Closed', 'Pending', 'Closed', '', '', '0000-00-00', '2024-10-03'),
(8, 'DL-229', 'TRACC_CONCERN', 'Sales', 'RGDI', '2', 'Gilbert Aaron Picardo Adane', 1, '2024-10-03', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Approved', 'Approved', 'Approved', '\n<h4>A PHP Error was encountered</h4>\n\n<p>Severity: Notice</p>\n<p>Message:  Undefined variable: disa', '', '0000-00-00', '2024-10-03'),
(9, 'DL-230', 'TRACC_CONCERN', 'Sales', 'LMI', 'www', 'test  users', 6, '2024-10-12', '', '', '', '', '', '0000-00-00', 'test users', '2024-10-14', '', 'Low', 'Closed', 'Approved', 'Closed', '', '', '0000-00-00', '2024-10-12'),
(10, 'DL-231', 'TRACC_CONCERN', 'Tracc', 'LMI', 'tracc', 'Gilbert Aaron Picardo Adane', 1, '2024-10-14', '', '', '', '', '', '0000-00-00', 'asdadwa', '2024-10-22', '', 'Low', 'Closed', 'Pending', 'Closed', '', '', '0000-00-00', '2024-10-14'),
(11, 'DL-232', 'TRACC_CONCERN', 'Tracc', 'LMI', 'ww', 'Gilbert Aaron Picardo Adane', 1, '2024-10-14', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Closed', 'Rejected', 'Closed', 'wwww', '', '0000-00-00', '2024-10-14'),
(12, 'DL-233', 'TRACC_CONCERN', 'Tracc', 'LPI', 'wwww', 'Gilbert Aaron Picardo Adane', 1, '2024-10-14', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Closed', 'Approved', 'Closed', '', '', '0000-00-00', '2024-10-14'),
(13, 'DL-234', 'TRACC_CONCERN', 'Tracc', 'LPI', 'aaw', 'Gilbert Aaron Picardo Adane', 1, '2024-10-14', '', '', '', '', '', '0000-00-00', 'Louises', '2024-10-26', '', 'Low', 'Closed', 'Approved', 'Closed', '', '', '0000-00-00', '2024-10-14'),
(14, 'DL-235', 'TRACC_CONCERN', 'Tracc', 'SV', 'www', 'Gilbert Aaron Picardo Adane', 1, '2024-10-14', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Closed', 'Rejected', 'Closed', '', '', '0000-00-00', '2024-10-14'),
(15, 'DL-236', 'TRACC_CONCERN', 'Tracc', 'RGDI', 'www', 'Gilbert Aaron Picardo Adane', 1, '2024-10-14', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Closed', 'Approved', 'Closed', '', '', '0000-00-00', '2024-10-14'),
(16, 'DL-237', 'TRACC_CONCERN', 'Tracc', 'RGDI', 'aa', 'Gilbert Aaron Picardo Adane', 1, '2024-10-14', '', '', '', 'ww', '', '0000-00-00', '', '0000-00-00', '', 'Medium', 'In Progress', 'Approved', 'Approved', '', '', '0000-00-00', '2024-10-14'),
(22, 'WH-2', 'TRACC_CONCERN', 'Sales', 'LMI', 'wwa', 'Gilbert Aaron Picardo Adane', 1, '2024-10-16', '1729059180_bgonepiece.jpg', '', '', '', '', '0000-00-00', 'Louise', '2024-11-04', '', 'Low', 'Closed', 'Pending', 'Closed', '', '', '0000-00-00', '2024-10-16'),
(23, 'WW-1', 'TRACC_CONCERN', 'Sales', 'LMI', 'ww', 'Gilbert Aaron Picardo Adane', 1, '2024-10-16', '1729063046_PHP_Programmer.docx', '', '', '', '', '0000-00-00', 'louise', '2024-11-25', '', 'Low', 'Closed', 'Pending', 'Closed', '', '', '0000-00-00', '2024-10-16'),
(24, '3-A', 'TRACC_CONCERN', 'Sales', 'LMI', 'ww', 'Gilbert Aaron Picardo Adane', 1, '2024-10-17', '1729135996_new_sched_jpg.png', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'High', 'In Progress', 'Approved', 'Approved', '', '', '0000-00-00', '2024-10-17'),
(25, '3-B', 'TRACC_CONCERN', 'Sales', 'LMI', 'w', 'Gilbert Aaron Picardo Adane', 1, '2024-10-17', '1729136019_Luffy.jpg', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Approved', 'Approved', 'Pending', '', '', '0000-00-00', '2024-10-17'),
(26, 'WH-1-2024', 'TRACC_CONCERN', 'TRACC', 'LMI', 'aa', 'Louie   Louie', 42, '2024-11-04', '', '', '', 'ww', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Done', 'Approved', 'Resolved', '', '', '0000-00-00', '2024-11-04'),
(27, 'IT-114', 'TRACC_CONCERN', 'Sales', 'LMI', 'si sir kel kups', 'Louise wis wis', 32, '2024-11-29', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Returned', 'Returned', 'Pending', '', '', '0000-00-00', '2024-11-29'),
(28, 'IT-112', 'TRACC_CONCERN', 'Sales', 'RGDI', 'awwaw', 'Gilbert Aaron Picardo Adane', 1, '2024-11-29', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Resolved', 'Returned', 'Pending', '', '', '0000-00-00', '2024-11-29'),
(29, 'WH-1-20242', 'TRACC_CONCERN', 'Tracc', 'LMI', '2', 'Gilbert Aaron Picardo Adane', 1, '2024-12-18', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 'Low', 'Open', 'Pending', 'Pending', '', '', '0000-00-00', '2024-12-18');

-- --------------------------------------------------------

--
-- Table structure for table `service_request_tracc_request`
--

CREATE TABLE `service_request_tracc_request` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `requested_by` varchar(150) NOT NULL,
  `department` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `date_requested` date NOT NULL,
  `date_needed` date NOT NULL,
  `requested_by_id` int(11) NOT NULL,
  `company` varchar(100) NOT NULL,
  `complete_details` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `accomplished_by` varchar(10) NOT NULL,
  `accomplished_by_date` date NOT NULL,
  `acknowledge_by` varchar(10) NOT NULL,
  `acknowledge_by_date` date NOT NULL,
  `priority` enum('Low','Medium','High') NOT NULL,
  `status` enum('Open','Approved','In Progress','Rejected','Resolved','Closed','Returned') NOT NULL,
  `approval_status` enum('Approved','Pending','Rejected','Returned') NOT NULL,
  `it_approval_status` enum('Approved','Pending','Rejected','Resolved','Closed') NOT NULL,
  `reason_reject_ticket` varchar(150) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_request_tracc_request`
--

INSERT INTO `service_request_tracc_request` (`recid`, `ticket_id`, `subject`, `requested_by`, `department`, `department_id`, `date_requested`, `date_needed`, `requested_by_id`, `company`, `complete_details`, `file`, `accomplished_by`, `accomplished_by_date`, `acknowledge_by`, `acknowledge_by_date`, `priority`, `status`, `approval_status`, `it_approval_status`, `reason_reject_ticket`, `created_at`) VALUES
(1, 'TRF-0001', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-11-20', '2024-11-20', 1, 'LMI,RGDI,LPI,SV', '', '', '', '0000-00-00', '', '0000-00-00', 'Low', 'Approved', 'Approved', 'Pending', '', '2024-11-20'),
(2, 'TRF-0002', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-11-21', '2024-11-21', 1, '', '', '', '', '0000-00-00', '', '0000-00-00', 'Low', 'Approved', 'Approved', 'Pending', '', '2024-11-21'),
(3, 'TRF-0003', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-11-23', '2024-11-23', 1, 'LMI,RGDI,LPI,SV', '', '', '', '0000-00-00', '', '0000-00-00', 'Low', 'Returned', 'Returned', 'Pending', '', '2024-11-23'),
(4, 'TRF-0004', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-11-23', '2024-11-23', 1, 'LMI,RGDI', 'ww', '', '', '0000-00-00', '', '0000-00-00', 'Low', 'Open', 'Pending', 'Pending', '', '2024-11-23'),
(5, 'TRF-0005', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-12-05', '2024-12-05', 1, 'LMI,RGDI,LPI,SV', '', '', '', '0000-00-00', '', '0000-00-00', 'Low', 'Open', 'Pending', 'Pending', '', '2024-12-05'),
(6, 'TRF-0006', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-12-18', '2024-12-18', 1, 'LMI,RGDI,LPI,SV', '', '', '', '0000-00-00', 'louise', '2024-12-18', 'Low', 'Open', 'Pending', 'Pending', '', '2024-12-18'),
(7, 'TRF-0007', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-12-18', '2024-12-18', 1, '', '', '', '', '0000-00-00', 'louise', '2024-12-18', 'Low', 'Open', 'Pending', 'Pending', '', '2024-12-18'),
(8, 'TRF-0008', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-12-18', '2024-12-18', 1, '', '', '', '', '0000-00-00', 'louise', '2024-12-18', 'Low', 'Open', 'Pending', 'Pending', '', '2024-12-18'),
(9, 'TRF-0009', 'TRACC_REQUEST', 'Gilbert Aaron Picardo Adane', 'Human Resources', 27, '2024-12-18', '2024-12-18', 1, '', '', '', '', '0000-00-00', 'louise', '2024-12-18', 'Low', 'Open', 'Pending', 'Pending', '', '2024-12-18');

-- --------------------------------------------------------

--
-- Table structure for table `tickets_approval_history`
--

CREATE TABLE `tickets_approval_history` (
  `id` int(11) NOT NULL,
  `recid` int(11) NOT NULL,
  `module` varchar(150) NOT NULL,
  `remarks` mediumtext,
  `status` varchar(255) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets_approval_history`
--

INSERT INTO `tickets_approval_history` (`id`, `recid`, `module`, `remarks`, `status`, `updated_by`, `created_date`) VALUES
(1, 31, 'msrf', '', 'Approved', 30, '2024-12-02 18:33:08'),
(2, 27, 'tracc-concern', '', 'Approved', 30, '2024-12-02 18:34:13'),
(3, 28, 'tracc-concern', '', 'Returned', 5, '2024-12-02 19:00:18'),
(4, 27, 'tracc-concern', '', 'Returned', 5, '2024-12-02 19:09:25'),
(5, 3, 'tracc-request', '', 'Returned', 30, '2024-12-02 19:19:46'),
(6, 3, 'tracc-request', '', 'Returned', 30, '2024-12-02 19:23:28'),
(7, 4, 'tracc-request', '', 'Approved', 5, '2024-12-15 23:21:28'),
(8, 32, 'msrf', '', 'Returned', 5, '2024-12-15 23:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_customer_req_form`
--

CREATE TABLE `tracc_req_customer_req_form` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(100) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `customer_code` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `tin_no` varchar(50) NOT NULL,
  `terms` varchar(50) NOT NULL,
  `customer_address` varchar(100) NOT NULL,
  `contact_person` varchar(50) NOT NULL,
  `office_tel_no` varchar(50) NOT NULL,
  `pricelist` bigint(20) NOT NULL,
  `payment_group` varchar(50) NOT NULL,
  `contact_no` varchar(50) NOT NULL,
  `territory` varchar(100) NOT NULL,
  `salesman` varchar(100) NOT NULL,
  `business_style` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `shipping_code` varchar(50) NOT NULL,
  `route_code` varchar(50) NOT NULL,
  `customer_shipping_address` varchar(100) NOT NULL,
  `landmark` varchar(100) NOT NULL,
  `window_time_start` time NOT NULL,
  `window_time_end` time NOT NULL,
  `special_instruction` varchar(50) NOT NULL,
  `remarks` varchar(50) DEFAULT 'Pending',
  `created_at` date NOT NULL,
  `approved_by` varchar(100) NOT NULL,
  `approved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_customer_req_form`
--

INSERT INTO `tracc_req_customer_req_form` (`recid`, `ticket_id`, `requested_by`, `company`, `date`, `customer_code`, `customer_name`, `tin_no`, `terms`, `customer_address`, `contact_person`, `office_tel_no`, `pricelist`, `payment_group`, `contact_no`, `territory`, `salesman`, `business_style`, `email`, `shipping_code`, `route_code`, `customer_shipping_address`, `landmark`, `window_time_start`, `window_time_end`, `special_instruction`, `remarks`, `created_at`, `approved_by`, `approved_date`) VALUES
(1, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI', '2024-11-21', 'a', 'a', 'ww', 'a', 'a', 'a', 'a', 0, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', '16:56:00', '16:56:00', 'aa', 'Done', '0000-00-00', '', '0000-00-00'),
(2, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LPI,SV', '2024-11-21', 'a', 'a', 'ww', 'a', 'a', 'a', 'a', 0, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', '16:58:00', '16:58:00', 'aa', 'Done', '0000-00-00', 'Louise Christian', '2024-12-17'),
(3, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI,SV', '2024-11-22', 'a', 'a', 'ww', 'a', 'a', 'a', 'a', 0, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', '10:10:00', '10:10:00', 'aa', 'Pending', '0000-00-00', '', '0000-00-00'),
(4, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI,SV', '2024-12-12', 'awit', 'louise regi', 'awit', 'a', '111', 'louise regi', 'louise regi', 0, 'louise regi', 'louise regi', 'louise regi', 'louise regi', 'louise regi', 'louise regi', 'louise regi', 'louise regi', 'louise regi', 'louise regi', '14:19:00', '14:19:00', 'louise regi', 'Pending', '2024-12-12', '', '2024-12-17');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_customer_req_form_del_days`
--

CREATE TABLE `tracc_req_customer_req_form_del_days` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(100) NOT NULL,
  `outright` tinyint(1) NOT NULL,
  `consignment` tinyint(1) NOT NULL,
  `customer_is_also_a_supplier` tinyint(1) NOT NULL,
  `online` tinyint(1) NOT NULL,
  `walk_in` tinyint(1) NOT NULL,
  `monday` tinyint(1) NOT NULL,
  `tuesday` tinyint(1) NOT NULL,
  `wednesday` tinyint(1) NOT NULL,
  `thursday` tinyint(1) NOT NULL,
  `friday` tinyint(1) NOT NULL,
  `saturday` tinyint(1) NOT NULL,
  `sunday` tinyint(1) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_customer_req_form_del_days`
--

INSERT INTO `tracc_req_customer_req_form_del_days` (`recid`, `ticket_id`, `outright`, `consignment`, `customer_is_also_a_supplier`, `online`, `walk_in`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `created_at`) VALUES
(1, 'TRF-0001', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, '0000-00-00'),
(2, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '0000-00-00'),
(3, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '0000-00-00'),
(4, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2024-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_customer_ship_setup`
--

CREATE TABLE `tracc_req_customer_ship_setup` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `shipping_code` varchar(50) NOT NULL,
  `route_code` varchar(50) NOT NULL,
  `customer_address` varchar(100) NOT NULL,
  `landmark` varchar(100) NOT NULL,
  `window_time_start` time NOT NULL,
  `window_time_end` time NOT NULL,
  `special_instruction` varchar(100) NOT NULL,
  `monday` tinyint(1) NOT NULL,
  `tuesday` tinyint(1) NOT NULL,
  `wednesday` tinyint(1) NOT NULL,
  `thursday` tinyint(1) NOT NULL,
  `friday` tinyint(1) NOT NULL,
  `saturday` tinyint(1) NOT NULL,
  `sunday` tinyint(1) NOT NULL,
  `remarks` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` date NOT NULL,
  `approved_by` varchar(100) NOT NULL,
  `approved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_customer_ship_setup`
--

INSERT INTO `tracc_req_customer_ship_setup` (`recid`, `ticket_id`, `requested_by`, `company`, `shipping_code`, `route_code`, `customer_address`, `landmark`, `window_time_start`, `window_time_end`, `special_instruction`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `remarks`, `created_at`, `approved_by`, `approved_date`) VALUES
(1, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,LPI', '1', '1', '22', '3', '11:11:00', '11:11:00', 'a', 0, 1, 0, 1, 0, 1, 0, 'Done', '0000-00-00', '', '0000-00-00'),
(2, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI', '1', '1', '22', '3', '23:32:00', '23:32:00', 'a', 1, 1, 1, 1, 1, 1, 1, 'Done', '0000-00-00', '', '0000-00-00'),
(3, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI', '1', '1', '22', '3', '11:33:00', '11:33:00', 'a', 1, 1, 1, 1, 1, 1, 1, 'Done', '2024-11-22', '', '0000-00-00'),
(4, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI', '222', '222', 'awawda', 'awad', '14:02:00', '14:03:00', 'awaw', 1, 1, 1, 1, 1, 1, 1, 'Done', '2024-12-05', 'Louise Christian', '2024-12-17'),
(5, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI', 'lmi112', 'aug123', 'caloocan', 'monumento', '11:16:00', '11:16:00', 'dapat sa gitna ka ng monumento', 1, 1, 1, 1, 1, 1, 1, '', '2024-12-09', '', '0000-00-00'),
(6, 'TRF-0003', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI,SVI', 'lmi1124', 'aug1234', 'caloocan city', 'monumento circle', '11:17:00', '23:17:00', 'dapat sa gitna ka ng monumento', 0, 1, 1, 0, 0, 1, 0, '', '2024-12-09', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_employee_req_form`
--

CREATE TABLE `tracc_req_employee_req_form` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` int(11) NOT NULL,
  `department_desc` varchar(50) NOT NULL,
  `position` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `tel_no_mob_no` varchar(50) NOT NULL,
  `tin_no` int(11) NOT NULL,
  `contact_person` varchar(50) NOT NULL,
  `remarks` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` date NOT NULL,
  `approved_by` varchar(100) NOT NULL,
  `approved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_employee_req_form`
--

INSERT INTO `tracc_req_employee_req_form` (`recid`, `ticket_id`, `requested_by`, `name`, `department`, `department_desc`, `position`, `address`, `tel_no_mob_no`, `tin_no`, `contact_person`, `remarks`, `created_at`, `approved_by`, `approved_date`) VALUES
(1, 'TRF-0004', 'Gilbert Aaron Picardo Adane', 'ww', 26, '', 'w', 'w', '12', 0, '12', 'Done', '2024-11-23', '', '0000-00-00'),
(2, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'louise regi', 1, '', 'PHP Programmer', '111', '231', 0, 'wa', 'Done', '2024-12-04', 'Louise Christian', '2024-12-17'),
(3, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'louise regi', 26, '', 'PHP Programmer', '111', '231', 0, 'wa', 'Done', '2024-12-04', 'Louise Christian', '2024-12-17'),
(4, 'TRF-0004', 'Gilbert Aaron Picardo Adane', 'louise regi', 27, 'Human Resources', 'PHP Programmer', '111', '231', 0, 'wa', 'Pending', '2024-12-04', '', '0000-00-00'),
(5, 'TRF-0004', 'Gilbert Aaron Picardo Adane', 'louise regi', 26, 'Finance', 'PHP Programmer', '111', '231', 0, 'wa', 'Pending', '2024-12-05', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_item_request_form`
--

CREATE TABLE `tracc_req_item_request_form` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `lmi_item_code` varchar(50) NOT NULL,
  `long_description` varchar(100) NOT NULL,
  `short_description` varchar(50) NOT NULL,
  `item_classification` varchar(50) NOT NULL,
  `item_sub_classification` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `merch_category` varchar(50) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `supplier_code` varchar(50) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `source` varchar(50) NOT NULL,
  `hs_code` varchar(50) NOT NULL,
  `unit_cost` float NOT NULL,
  `selling_price` float NOT NULL,
  `major_item_group` varchar(50) NOT NULL,
  `item_sub_group` varchar(50) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `sales` varchar(50) NOT NULL,
  `sales_return` varchar(50) NOT NULL,
  `purchases` varchar(50) NOT NULL,
  `purchase_return` varchar(50) NOT NULL,
  `cgs` varchar(50) NOT NULL,
  `inventory` varchar(50) NOT NULL,
  `sales_disc` varchar(50) NOT NULL,
  `gl_department` varchar(50) NOT NULL,
  `capacity_per_pallet` varchar(50) NOT NULL,
  `remarks` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` date NOT NULL,
  `approved_by` varchar(100) NOT NULL,
  `approved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_item_request_form`
--

INSERT INTO `tracc_req_item_request_form` (`recid`, `ticket_id`, `requested_by`, `company`, `date`, `lmi_item_code`, `long_description`, `short_description`, `item_classification`, `item_sub_classification`, `department`, `merch_category`, `brand`, `supplier_code`, `supplier_name`, `class`, `tag`, `source`, `hs_code`, `unit_cost`, `selling_price`, `major_item_group`, `item_sub_group`, `account_type`, `sales`, `sales_return`, `purchases`, `purchase_return`, `cgs`, `inventory`, `sales_disc`, `gl_department`, `capacity_per_pallet`, `remarks`, `created_at`, `approved_by`, `approved_date`) VALUES
(1, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI,SV', '2024-12-09', 'LMI-29', 'lotion', 'lotion ni chinchan', 'item', 'item sub', 'ewan', 'ewan', 'megan', '2YSBS', 'kel', 'kel', 'one', 'of income', '123Y ', 12340.2, 16000, 'ewan', 'ewan', 'ewan', 'ewan', 'ewan', 'ewan ko rin ew', 'ewan', 'ewan ', 'goods', 'ewan', 'ewan', '1 per box? ', 'Done', '2024-12-09', '', '0000-00-00'),
(2, 'TRF-0003', 'Gilbert Aaron Picardo Adane', 'LMI,SV', '2024-12-09', 'LMI-29', 'lotion', 'lotion ni chinchan', 'item', 'item sub', 'ewan', 'ewan', 'megan', '2YSBS', 'kel', 'kel', 'one', 'of income', '123Y ', 12340.2, 16000, 'ewan', 'ewan', 'ewan', 'ewan', 'ewan', 'ewan ko rin ew', 'ewan', 'ewan ', 'goods', 'ewan', 'ewan', '1 per box? ', 'Done', '2024-12-09', 'Louise Christian', '2024-12-17'),
(3, 'TRF-0003', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI,SV', '2024-12-12', '22-1', '22', '22', '22', '22', '22', '22', '22', '22', '22', '22', '22', '22', '22', 22, 22, '22', '22', '22', '22', '', '', '', '', '', '', '', '', 'Pending', '2024-12-12', 'Louise Christian', '2024-12-17');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_item_request_form_checkboxes`
--

CREATE TABLE `tracc_req_item_request_form_checkboxes` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `inventory` tinyint(1) NOT NULL,
  `non_inventory` tinyint(1) NOT NULL,
  `services` tinyint(1) NOT NULL,
  `charges` tinyint(1) NOT NULL,
  `watsons` tinyint(1) NOT NULL,
  `other_accounts` tinyint(1) NOT NULL,
  `online` tinyint(1) NOT NULL,
  `all_accounts` tinyint(1) NOT NULL,
  `trade` tinyint(1) NOT NULL,
  `non_trade` tinyint(1) NOT NULL,
  `yes` tinyint(1) NOT NULL,
  `no` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_item_request_form_checkboxes`
--

INSERT INTO `tracc_req_item_request_form_checkboxes` (`recid`, `ticket_id`, `inventory`, `non_inventory`, `services`, `charges`, `watsons`, `other_accounts`, `online`, `all_accounts`, `trade`, `non_trade`, `yes`, `no`) VALUES
(1, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'TRF-0003', 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1, 0),
(3, 'TRF-0003', 1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_item_req_form_gl_setup`
--

CREATE TABLE `tracc_req_item_req_form_gl_setup` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `uom` varchar(50) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `length` float NOT NULL,
  `height` float NOT NULL,
  `width` float NOT NULL,
  `weight` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_item_req_form_gl_setup`
--

INSERT INTO `tracc_req_item_req_form_gl_setup` (`recid`, `ticket_id`, `uom`, `barcode`, `length`, `height`, `width`, `weight`) VALUES
(1, 'TRF-0001', '1 per box', '211w', 20, 10, 20, 500),
(2, 'TRF-0001', '2 per sachet', 'l2kw', 20, 20, 20, 500),
(3, 'TRF-0003', '1 per boxes', '211ww', 201, 101, 202, 5002),
(4, 'TRF-0003', '2 per sachetess', 'l2kws', 201, 202, 2034, 5004);

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_item_req_form_whs_setup`
--

CREATE TABLE `tracc_req_item_req_form_whs_setup` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `warehouse` varchar(50) NOT NULL,
  `warehouse_no` int(11) NOT NULL,
  `storage_location` varchar(50) NOT NULL,
  `storage_type` varchar(50) NOT NULL,
  `fixed_bin` varchar(50) NOT NULL,
  `min_qty` int(11) NOT NULL,
  `max_qty` int(11) NOT NULL,
  `replen_qty` int(11) NOT NULL,
  `control_qty` int(11) NOT NULL,
  `round_qty` int(11) NOT NULL,
  `uom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_item_req_form_whs_setup`
--

INSERT INTO `tracc_req_item_req_form_whs_setup` (`recid`, `ticket_id`, `warehouse`, `warehouse_no`, `storage_location`, `storage_type`, `fixed_bin`, `min_qty`, `max_qty`, `replen_qty`, `control_qty`, `round_qty`, `uom`) VALUES
(1, 'TRF-0001', 'goods', 1, 'goods', 'dry', '1', 1, 2, 1, 1, 1, '1 per pcs'),
(2, 'TRF-0003', 'goodseses', 123, 'goodssds', 'dryedse', '12', 13, 24, 15, 16, 12, '1 per pcseses');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_mf_account`
--

CREATE TABLE `tracc_req_mf_account` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `tracc_orientation` tinyint(1) NOT NULL,
  `lmi` tinyint(1) NOT NULL,
  `rgdi` tinyint(1) NOT NULL,
  `lpi` tinyint(1) NOT NULL,
  `sv` tinyint(1) NOT NULL,
  `gps_account` tinyint(1) NOT NULL,
  `others` tinyint(1) NOT NULL,
  `others_description_acc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_mf_account`
--

INSERT INTO `tracc_req_mf_account` (`recid`, `ticket_id`, `tracc_orientation`, `lmi`, `rgdi`, `lpi`, `sv`, `gps_account`, `others`, `others_description_acc`) VALUES
(1, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 'aw'),
(2, 'TRF-0002', 0, 0, 0, 0, 0, 0, 0, ''),
(3, 'TRF-0003', 1, 1, 1, 1, 1, 1, 1, 'ww'),
(4, 'TRF-0004', 0, 1, 1, 0, 0, 0, 0, ''),
(5, 'TRF-0005', 0, 0, 0, 0, 0, 0, 0, ''),
(6, 'TRF-0006', 1, 1, 0, 0, 0, 1, 0, ''),
(7, 'TRF-0007', 0, 0, 0, 0, 0, 0, 0, ''),
(8, 'TRF-0008', 0, 0, 0, 0, 0, 0, 0, ''),
(9, 'TRF-0009', 0, 0, 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_mf_new_add`
--

CREATE TABLE `tracc_req_mf_new_add` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `item` tinyint(1) NOT NULL,
  `customer` tinyint(1) NOT NULL,
  `supplier` tinyint(1) NOT NULL,
  `warehouse` tinyint(1) NOT NULL,
  `bin_number` tinyint(1) NOT NULL,
  `customer_shipping_setup` tinyint(1) NOT NULL,
  `employee_request_form` tinyint(1) NOT NULL,
  `others` tinyint(1) NOT NULL,
  `others_description_add` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_mf_new_add`
--

INSERT INTO `tracc_req_mf_new_add` (`recid`, `ticket_id`, `item`, `customer`, `supplier`, `warehouse`, `bin_number`, `customer_shipping_setup`, `employee_request_form`, `others`, `others_description_add`) VALUES
(1, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 1, 'aw'),
(2, 'TRF-0002', 1, 1, 0, 0, 0, 0, 0, 0, ''),
(3, 'TRF-0003', 1, 1, 1, 1, 1, 1, 0, 1, 'ww'),
(4, 'TRF-0004', 1, 1, 0, 0, 0, 0, 0, 0, ''),
(5, 'TRF-0005', 0, 0, 1, 0, 0, 0, 0, 0, ''),
(6, 'TRF-0006', 0, 0, 0, 0, 0, 1, 0, 0, ''),
(7, 'TRF-0007', 1, 1, 1, 1, 1, 1, 1, 0, ''),
(8, 'TRF-0008', 1, 1, 1, 1, 1, 1, 1, 0, ''),
(9, 'TRF-0009', 1, 1, 1, 1, 1, 1, 1, 1, 'w');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_mf_update`
--

CREATE TABLE `tracc_req_mf_update` (
  `recid` int(20) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `system_date_lock` tinyint(1) NOT NULL,
  `user_file_access` tinyint(1) NOT NULL,
  `item_details` tinyint(1) NOT NULL,
  `customer_details` tinyint(1) NOT NULL,
  `supplier_details` tinyint(1) NOT NULL,
  `employee_details` tinyint(1) NOT NULL,
  `others` tinyint(1) NOT NULL,
  `others_description_update` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_mf_update`
--

INSERT INTO `tracc_req_mf_update` (`recid`, `ticket_id`, `system_date_lock`, `user_file_access`, `item_details`, `customer_details`, `supplier_details`, `employee_details`, `others`, `others_description_update`) VALUES
(1, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 'aw'),
(2, 'TRF-0002', 0, 0, 0, 0, 0, 0, 0, ''),
(3, 'TRF-0003', 1, 1, 1, 1, 1, 1, 1, 'ww'),
(4, 'TRF-0004', 1, 1, 0, 0, 0, 0, 0, ''),
(5, 'TRF-0005', 0, 0, 0, 0, 0, 0, 0, ''),
(6, 'TRF-0006', 0, 0, 0, 1, 0, 0, 0, ''),
(7, 'TRF-0007', 0, 0, 0, 0, 0, 0, 0, ''),
(8, 'TRF-0008', 0, 0, 0, 0, 0, 0, 0, ''),
(9, 'TRF-0009', 0, 0, 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_supplier_req_form`
--

CREATE TABLE `tracc_req_supplier_req_form` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `supplier_code` varchar(100) NOT NULL,
  `supplier_account_group` varchar(50) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `country_origin` varchar(50) NOT NULL,
  `supplier_address` varchar(100) NOT NULL,
  `office_tel` varchar(50) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `terms` varchar(50) NOT NULL,
  `tin_no` int(11) NOT NULL,
  `pricelist` float NOT NULL,
  `ap_account` varchar(50) NOT NULL,
  `ewt` varchar(50) NOT NULL,
  `advance_account` varchar(50) NOT NULL,
  `vat` float NOT NULL,
  `non_vat` tinyint(1) NOT NULL,
  `payee_1` varchar(50) NOT NULL,
  `payee_2` varchar(50) NOT NULL,
  `payee_3` varchar(50) NOT NULL,
  `driver_name` varchar(50) NOT NULL,
  `driver_contact_no` varchar(50) NOT NULL,
  `driver_fleet` varchar(50) NOT NULL,
  `driver_plate_no` varchar(50) NOT NULL,
  `helper_name` varchar(50) NOT NULL,
  `helper_contact_no` varchar(50) NOT NULL,
  `helper_rate_card` varchar(50) NOT NULL,
  `remarks` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` date NOT NULL,
  `approved_by` varchar(100) NOT NULL,
  `approved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_supplier_req_form`
--

INSERT INTO `tracc_req_supplier_req_form` (`recid`, `ticket_id`, `requested_by`, `company`, `date`, `supplier_code`, `supplier_account_group`, `supplier_name`, `country_origin`, `supplier_address`, `office_tel`, `zip_code`, `contact_person`, `terms`, `tin_no`, `pricelist`, `ap_account`, `ewt`, `advance_account`, `vat`, `non_vat`, `payee_1`, `payee_2`, `payee_3`, `driver_name`, `driver_contact_no`, `driver_fleet`, `driver_plate_no`, `helper_name`, `helper_contact_no`, `helper_rate_card`, `remarks`, `created_at`, `approved_by`, `approved_date`) VALUES
(1, 'TRF-0001', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI,SV', '2024-12-04', 'aw', 'aw', 'aw', 'aw', 'aw', 'aw', 1, 'aw', 'aw', 1, 1, 'aw', 'aw', 'aw', 1, 1, 'aw', 'aw', 'aw', 'aw', 'awa', 'aw', 'aw', 'aw', 'aw', 'aw', 'Pending', '0000-00-00', 'Louise Christian', '2024-12-17'),
(2, 'TRF-0003', 'Gilbert Aaron Picardo Adane', 'LMI,RGDI,LPI,SV', '2024-12-12', '', '', '', '', '', '', 0, '', '', 0, 0, '', '', '', 0, 1, '', '', '', '', '', '', '', '', '', '', 'Pending', '2024-12-12', 'Louise Christian', '2024-12-17');

-- --------------------------------------------------------

--
-- Table structure for table `tracc_req_supplier_req_form_checkboxes`
--

CREATE TABLE `tracc_req_supplier_req_form_checkboxes` (
  `recid` int(11) NOT NULL,
  `ticket_id` varchar(50) NOT NULL,
  `supplier_group_local` tinyint(1) NOT NULL,
  `supplier_group_foreign` tinyint(1) NOT NULL,
  `supplier_trade` tinyint(1) NOT NULL,
  `supplier_non_trade` tinyint(1) NOT NULL,
  `trade_type_goods` tinyint(1) NOT NULL,
  `trade_type_services` tinyint(1) NOT NULL,
  `trade_type_goods_services` tinyint(1) NOT NULL,
  `major_grp_local_trade_vendor` tinyint(1) NOT NULL,
  `major_grp_local_non_trade_vendor` tinyint(1) NOT NULL,
  `major_grp_foreign_trade_vendors` tinyint(1) NOT NULL,
  `major_grp_foreign_non_trade_vendors` tinyint(1) NOT NULL,
  `major_grp_local_broker_forwarder` tinyint(1) NOT NULL,
  `major_grp_rental` tinyint(1) NOT NULL,
  `major_grp_bank` tinyint(1) NOT NULL,
  `major_grp_ot_supplier` tinyint(1) NOT NULL,
  `major_grp_government_offices` tinyint(1) NOT NULL,
  `major_grp_insurance` tinyint(1) NOT NULL,
  `major_grp_employees` tinyint(1) NOT NULL,
  `major_grp_sub_aff_intercompany` tinyint(1) NOT NULL,
  `major_grp_utilities` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tracc_req_supplier_req_form_checkboxes`
--

INSERT INTO `tracc_req_supplier_req_form_checkboxes` (`recid`, `ticket_id`, `supplier_group_local`, `supplier_group_foreign`, `supplier_trade`, `supplier_non_trade`, `trade_type_goods`, `trade_type_services`, `trade_type_goods_services`, `major_grp_local_trade_vendor`, `major_grp_local_non_trade_vendor`, `major_grp_foreign_trade_vendors`, `major_grp_foreign_non_trade_vendors`, `major_grp_local_broker_forwarder`, `major_grp_rental`, `major_grp_bank`, `major_grp_ot_supplier`, `major_grp_government_offices`, `major_grp_insurance`, `major_grp_employees`, `major_grp_sub_aff_intercompany`, `major_grp_utilities`) VALUES
(1, 'TRF-0001', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'TRF-0003', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `recid` int(11) NOT NULL,
  `emp_id` varchar(10) DEFAULT NULL,
  `fname` varchar(150) NOT NULL,
  `mname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `position` varchar(150) NOT NULL,
  `username` varchar(155) NOT NULL,
  `password` varchar(255) NOT NULL,
  `s_password` varchar(255) NOT NULL,
  `api_password` varchar(255) NOT NULL,
  `s_api_password` varchar(255) NOT NULL,
  `department_description` varchar(150) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `sup_id` varchar(11) NOT NULL,
  `role` enum('L1','L2','L3','L4') DEFAULT NULL,
  `status` int(11) NOT NULL,
  `failed_attempts` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`recid`, `emp_id`, `fname`, `mname`, `lname`, `email`, `position`, `username`, `password`, `s_password`, `api_password`, `s_api_password`, `department_description`, `dept_id`, `sup_id`, `role`, `status`, `failed_attempts`, `created_at`, `updated_at`) VALUES
(1, '24-0403A', 'Gilbert Aaron', 'Picardo', 'Adane', 'gilbert.lifestrong@gmail.com', 'SR. Programmer', 'GAdane', '$2y$10$aEJCjelbMMdeELDp.AsLYuNhUmpaV49Kw3bx8r8ewT47v4XvgPFsm', 'wms123', '$2y$10$g9EzYqWq.M/SKyiLvOLltupb25hAoe3FLNX/5Fc/MsW0mLOaYOZJ2', 'wms123', '', 27, '2', 'L1', 1, 1, '2024-05-16', '2024-10-23'),
(4, '24-0001', 'Mark Eric', '', 'Requinto', 'eric.lifestrong@gmail.com', 'Supervisor', 'ERequinto', '$2y$10$I9GuwOQzK3g3AIF6e1rLyun2QdHIPJxolu7FuzbQAjNcFxVkJpbIq', 'wms123', '$2y$10$I9GuwOQzK3g3AIF6e1rLyun2QdHIPJxolu7FuzbQAjNcFxVkJpbIq', 'wms123', '', 2, '', 'L2', 1, 1, '2024-05-20', NULL),
(5, '23-0001', 'Test ', 'Test', 'Data', 'test.lifestrong@gmail.com', 'Supervisor', 'testdata123', '$2y$10$pG2wTlohWYgd3eKoZXGvtOTjW2nnnHSgU0Hljks85nIHxN0/5xWOe', 'wms123', '$2y$10$pG2wTlohWYgd3eKoZXGvtOTjW2nnnHSgU0Hljks85nIHxN0/5xWOe', 'wms123', '', 1, '', 'L2', 1, 1, '2024-05-22', NULL),
(6, '22-0001', 'test', '', 'users', 'testusers.lifestrong@gmail.com', 'Supervisor', 'testusers123', '$2y$10$HZ4EEwXzz9mwhtoMiyPbBeA4Ih463r/RPxGdqOSkj3qTZx3xA7nH.', 'wms123', '$2y$10$2svaoB93/cH0E9XylecwB.ik2jRxB.JRhwnst9MGXSzQmhDclObGW', 'wms123', '', 27, '', 'L1', 1, 1, '2024-05-30', '2024-09-06'),
(7, '23-0926', 'Dan Mark', 'E', 'Delos Ama', 'ictvalidator.lifestrong@gmail.com', 'Validator', 'DMDelosAma', '$2y$10$BBGc7GfRAQbg.R7Lp/CEuOBIfA780zc/r.TXlKWX4e0Ux9fWNMLy6', 'dan123', '$2y$10$pGjjRUnMiYSMQx0cXXfJ/eoHw5RhscpmA06jxk/If.atCtcgBCaUW', 'dan123', '', 1, '1', 'L1', 1, 1, '2024-06-05', '2024-09-05'),
(8, '24-0206', 'Robert Vien', '', 'Santiago', 'robertvien@lifestrong.com', 'JR. Programmer', 'Robert', '$2y$10$sI6.YPnKZQKuSebV2eXP3.5k7p9mMAHbOGnApPRMNgAcE4WjhTdti', 'wms123', '$2y$10$OuTMbOlhza2cENNFapxAF.5HaLG2YhY/zpjOGhmgNIH8qRWCD/TMe', 'wms123', '', 1, '1', 'L1', 1, 1, '2024-06-05', '2024-09-10'),
(27, 'L4', 'Christian', '', 'Janer', '', 'IT Assistant', 'tian', '$2y$10$bSHfT.4EFeZm5DSiHLkruO2mW/Q3J4I.9kOnyTNXbqyNO0T3Eh2rC', 'tian', '$2y$10$bSHfT.4EFeZm5DSiHLkruO2mW/Q3J4I.9kOnyTNXbqyNO0T3Eh2rC', 'tian', '', 1, '23-0001', 'L1', 1, 1, '2024-09-07', '2024-10-23'),
(30, 'L3', 'l3admin', '', 'l3admin', 'try@gmail.com', 'Admin Head', 'l3admin', '$2y$10$ekTsV2da6f202uTTcxGFM.sBy5JzHDm1a5VjseJY5y0UkQJNn.czC', 'l3admin', '$2y$10$/JoQoRdNAGSBQDf6O7Tmyej7MnUNd1caY74Ha0b3Z6P61Yhat6OY6', 'l3admin', '', 26, '3', 'L3', 1, 1, '2024-10-03', '2024-10-23'),
(32, '232', 'Louise', 'wis', 'wis', '', 'Progra', 'wisss', '$2y$10$GgeRT7QdD9lmqz4kHpw3Z.LuR2zO7FcW0b9S8ZEmW/JoGBh9/T2yO', '', '$2y$10$GgeRT7QdD9lmqz4kHpw3Z.LuR2zO7FcW0b9S8ZEmW/JoGBh9/T2yO', '', 'Accounting', 48, '', 'L1', 0, 1, '2024-11-29', NULL),
(35, '2525', 'Louise', 'Jimenez', 'Regi', 'louise.lifestrong@gmail.com', 'mema', 'wis', '$2y$10$STA4uzeLGqfExMHEqSKNMOjUsXvCAV0Tsn.0jZ6IXXs6V0WVHZC6W', '', '$2y$10$STA4uzeLGqfExMHEqSKNMOjUsXvCAV0Tsn.0jZ6IXXs6V0WVHZC6W', '', 'Finance', 26, '3', 'L1', 1, 1, '2024-12-13', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `filled_by_mis`
--
ALTER TABLE `filled_by_mis`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `service_request_msrf`
--
ALTER TABLE `service_request_msrf`
  ADD PRIMARY KEY (`recid`),
  ADD KEY `approver_id` (`sup_id`),
  ADD KEY `it_staff` (`assigned_it_staff`),
  ADD KEY `it_sup` (`it_sup_id`),
  ADD KEY `it_dept` (`it_dept_id`);

--
-- Indexes for table `service_request_tracc_concern`
--
ALTER TABLE `service_request_tracc_concern`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `service_request_tracc_request`
--
ALTER TABLE `service_request_tracc_request`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tickets_approval_history`
--
ALTER TABLE `tickets_approval_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_updated_by` (`updated_by`);

--
-- Indexes for table `tracc_req_customer_req_form`
--
ALTER TABLE `tracc_req_customer_req_form`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_customer_req_form_del_days`
--
ALTER TABLE `tracc_req_customer_req_form_del_days`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_customer_ship_setup`
--
ALTER TABLE `tracc_req_customer_ship_setup`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_employee_req_form`
--
ALTER TABLE `tracc_req_employee_req_form`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_item_request_form`
--
ALTER TABLE `tracc_req_item_request_form`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_item_request_form_checkboxes`
--
ALTER TABLE `tracc_req_item_request_form_checkboxes`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_item_req_form_gl_setup`
--
ALTER TABLE `tracc_req_item_req_form_gl_setup`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_item_req_form_whs_setup`
--
ALTER TABLE `tracc_req_item_req_form_whs_setup`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_mf_account`
--
ALTER TABLE `tracc_req_mf_account`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_mf_new_add`
--
ALTER TABLE `tracc_req_mf_new_add`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_mf_update`
--
ALTER TABLE `tracc_req_mf_update`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_supplier_req_form`
--
ALTER TABLE `tracc_req_supplier_req_form`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tracc_req_supplier_req_form_checkboxes`
--
ALTER TABLE `tracc_req_supplier_req_form_checkboxes`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`recid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `filled_by_mis`
--
ALTER TABLE `filled_by_mis`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `service_request_msrf`
--
ALTER TABLE `service_request_msrf`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `service_request_tracc_concern`
--
ALTER TABLE `service_request_tracc_concern`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `service_request_tracc_request`
--
ALTER TABLE `service_request_tracc_request`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tickets_approval_history`
--
ALTER TABLE `tickets_approval_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tracc_req_customer_req_form`
--
ALTER TABLE `tracc_req_customer_req_form`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tracc_req_customer_req_form_del_days`
--
ALTER TABLE `tracc_req_customer_req_form_del_days`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tracc_req_customer_ship_setup`
--
ALTER TABLE `tracc_req_customer_ship_setup`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tracc_req_employee_req_form`
--
ALTER TABLE `tracc_req_employee_req_form`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tracc_req_item_request_form`
--
ALTER TABLE `tracc_req_item_request_form`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tracc_req_item_request_form_checkboxes`
--
ALTER TABLE `tracc_req_item_request_form_checkboxes`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tracc_req_item_req_form_gl_setup`
--
ALTER TABLE `tracc_req_item_req_form_gl_setup`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tracc_req_item_req_form_whs_setup`
--
ALTER TABLE `tracc_req_item_req_form_whs_setup`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracc_req_mf_account`
--
ALTER TABLE `tracc_req_mf_account`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tracc_req_mf_new_add`
--
ALTER TABLE `tracc_req_mf_new_add`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tracc_req_mf_update`
--
ALTER TABLE `tracc_req_mf_update`
  MODIFY `recid` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tracc_req_supplier_req_form`
--
ALTER TABLE `tracc_req_supplier_req_form`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracc_req_supplier_req_form_checkboxes`
--
ALTER TABLE `tracc_req_supplier_req_form_checkboxes`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets_approval_history`
--
ALTER TABLE `tickets_approval_history`
  ADD CONSTRAINT `fk_user_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`recid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
