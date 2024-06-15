#!/usr/bin/python3
import argparse
import getpass
import hashlib
import os
import platform
import subprocess
import sys
from enum import Enum
from typing import List, Optional, Tuple


class ConfigFiles(Enum):
	CONFIG = "config.toml"
	SECURE_CONFIG = "secure_config.toml"


class LinuxRepos(Enum):
	BIONIC = "bionic-agent"
	FOCAL = "focal-agent"
	JAMMY = "jammy-agent"
	BULLSEYE = "bullseye-agent"
	BUSTER = "buster-agent"
	BOOKWORM = "bookworm-agent"
	OOTPA = "ootpa"
	MAIPO = "maipo"
	PLOW = "plow"


class OSCodeNames(Enum):
	UBUNTU = "Ubuntu"
	DEBIAN = "Debian"
	REDHAT = "RedHat"
	MACOS = "Darwin"


SUPPORTED_ARCHITECTURES = {
	"x86_64": {"mac_pkg_url": "https://updates.axcient.cloud/xcloud-agent/xcloud-agent-mac_x64.pkg"},
	"arm64": {"mac_pkg_url": "https://updates.axcient.cloud/xcloud-agent/xcloud-agent-mac_arm.pkg"}
}

SUPPORTED_SYSTEMS = {
	"Darwin": {"os": OSCodeNames.MACOS},
	"Ubuntu 18.04": {"repo": LinuxRepos.BIONIC, "os": OSCodeNames.UBUNTU},
	"Ubuntu 20.04": {"repo": LinuxRepos.FOCAL, "os": OSCodeNames.UBUNTU},
	"Ubuntu 22.04": {"repo": LinuxRepos.JAMMY, "os": OSCodeNames.UBUNTU},
	"Red Hat Enterprise Linux 9": {
		"repo": LinuxRepos.PLOW, "os": OSCodeNames.REDHAT, "epel_version": 9, "exist_refresh": True},
	"Red Hat Enterprise Linux 8": {
		"repo": LinuxRepos.OOTPA, "os": OSCodeNames.REDHAT, "epel_version": 8, "exist_refresh": True},
	"Red Hat Enterprise Linux 7": {
		"repo": LinuxRepos.MAIPO, "os": OSCodeNames.REDHAT, "epel_version": 7, "exist_refresh": False},
	"Debian GNU/Linux 10 (buster)": {"repo": LinuxRepos.BUSTER, "os": OSCodeNames.DEBIAN},
	"Debian GNU/Linux 11 (bullseye)": {"repo": LinuxRepos.BULLSEYE, "os": OSCodeNames.DEBIAN},
	"Debian GNU/Linux 12 (bookworm)": {"repo": LinuxRepos.BOOKWORM, "os": OSCodeNames.DEBIAN},
	"Linux Mint 19": {"repo": LinuxRepos.BIONIC, "os": OSCodeNames.UBUNTU},
	"Linux Mint 20": {"repo": LinuxRepos.FOCAL, "os": OSCodeNames.UBUNTU},
	"Linux Mint 21": {"repo": LinuxRepos.JAMMY, "os": OSCodeNames.UBUNTU},
	"Zorin OS 15": {"repo": LinuxRepos.BIONIC, "os": OSCodeNames.UBUNTU},
	"Zorin OS 16": {"repo": LinuxRepos.FOCAL, "os": OSCodeNames.UBUNTU},
	"elementary OS 5": {"repo": LinuxRepos.BIONIC, "os": OSCodeNames.UBUNTU},
	"elementary OS 6": {"repo": LinuxRepos.FOCAL, "os": OSCodeNames.UBUNTU},
	"elementary OS 7": {"repo": LinuxRepos.JAMMY, "os": OSCodeNames.UBUNTU},
	"AlmaLinux 8": {"repo": LinuxRepos.OOTPA, "os": OSCodeNames.REDHAT, "epel_version": 8, "exist_refresh": True},
	"AlmaLinux 9": {"repo": LinuxRepos.PLOW, "os": OSCodeNames.REDHAT, "epel_version": 9, "exist_refresh": True},
	"Oracle Linux 7": {"repo": LinuxRepos.MAIPO, "os": OSCodeNames.REDHAT, "epel_version": 7, "exist_refresh": False},
	"Oracle Linux 8": {"repo": LinuxRepos.OOTPA, "os": OSCodeNames.REDHAT, "epel_version": 8, "exist_refresh": True},
	"Oracle Linux 9": {"repo": LinuxRepos.PLOW, "os": OSCodeNames.REDHAT, "epel_version": 9, "exist_refresh": True},
	"Rocky-Linux-8": {"repo": LinuxRepos.OOTPA, "os": OSCodeNames.REDHAT, "epel_version": 8, "exist_refresh": True},
	"Rocky-Linux-9": {"repo": LinuxRepos.PLOW, "os": OSCodeNames.REDHAT, "epel_version": 9, "exist_refresh": True},
	"CentOS-7": {"repo": LinuxRepos.MAIPO, "os": OSCodeNames.REDHAT, "epel_version": 7, "exist_refresh": False}
}


def check_system_requirements() -> Optional[str]:
	if not is_root():
		print("Root privileges are needed. Rerun with su/sudo.")
		return None

	if not is_architecture_supported():
		print("Unsupported architecture")
		return None

	supported, system_name = is_system_supported()
	if not supported:
		if not request_yes_or_no("This is not a supported operating system, proceed with installation? (y/n): "):
			return None

		print("Continue installation in unsupported system.")

	return system_name


def is_root() -> bool:
	return os.getegid() == 0


def is_architecture_supported() -> bool:
	return os.uname().machine in SUPPORTED_ARCHITECTURES


def is_system_supported() -> Tuple[bool, str]:
	if is_macos():
		return True, OSCodeNames.MACOS.value

	content = get_linux_os_release_content()
	for supported_name in SUPPORTED_SYSTEMS:
		if supported_name in content:
			return True, supported_name

	return False, get_linux_system_name(content)


def is_macos() -> bool:
	return platform.system() == OSCodeNames.MACOS.value


def get_linux_os_release_content() -> str:
	filename = "/etc/os-release"
	if not os.path.isfile(filename):
		raise Exception(f"{filename} file does not exist")

	with open(filename, "r") as file:
		return file.read()


def get_linux_system_name(os_release_content: str) -> str:
	prefix = "PRETTY_NAME="
	for line in os_release_content.splitlines():
		if line.startswith(prefix):
			return line[len(prefix):-1]

	return 'Unsupported Linux system'


def request_yes_or_no(message: str) -> bool:
	while True:
		answer = input(message).strip().lower()
		if answer in ("y", "yes"):
			return True

		if answer in ("n", "no"):
			return False


def get_script_args(system_name: str) -> argparse.Namespace:
	parser = argparse.ArgumentParser(description="install_agent")

	if system_name == OSCodeNames.MACOS.value:
		parser.add_argument(
			"--pkg_url", help="URL for downloading the package.", type=str, default=default_mac_pkg_url())
	else:
		parser.add_argument(
			"--repo-name", "-r", help="Repository name (dev/stg). Default is production repository.",
			type=str, default=default_linux_repo(system_name))

	parser.add_argument("--appliance-address", "-a", help="Appliance address.", type=str)
	parser.add_argument("--token-id", "-t", help="Registration token address for direct to cloud mode.", type=str)
	parser.add_argument("--auth-url", "-u", help="Auth service URL.", type=str)
	parser.add_argument("--password", "-p", help="Protected system password.", type=str, nargs='?', const='')
	parser.add_argument("--local", "-l", help="Local package for installation.", type=str)
	parser.add_argument(
		"--update", "-m", help="Update mode (turns on automatically, fix repo name after OS upgrade)", type=str)

	args = parser.parse_args()

	if system_name != OSCodeNames.MACOS.value and args.repo_name in ["dev", "stg"]:
		args.repo_name = f"{default_linux_repo(system_name)}-{args.repo_name}"

	if args.appliance_address is None and args.token_id is None and args.update is None:
		if request_install_mode():
			args.token_id = input("Please, enter the address of the registration token: ")
		else:
			args.appliance_address = input("Please, enter the Appliance address: ")

	args.auth_url = args.auth_url or "https://api.axcient.com/auth/tokens"

	if args.password == "":
		args.password = getpass.getpass("Please, enter protected system password (may be empty): ")
	if args.password is None:
		args.password = ""

	return args


def default_mac_pkg_url() -> str:
	architecture = os.uname().machine
	return SUPPORTED_ARCHITECTURES.get(architecture, {}).get('mac_pkg_url', '')


def default_linux_repo(system_name: str) -> str:
	system_attributes = SUPPORTED_SYSTEMS.get(system_name, {})
	if system_attributes:
		return system_attributes.get('repo', LinuxRepos.FOCAL).value

	if is_rpm_system(system_name):
		return LinuxRepos.OOTPA.value

	return LinuxRepos.FOCAL.value


def is_rpm_system(system_name: str) -> bool:
	os_system = SUPPORTED_SYSTEMS.get(system_name, {}).get('os')
	if os_system == OSCodeNames.REDHAT:
		return True

	result = subprocess.run(["rpm -qf /bin/ls >/dev/null 2>&1"], shell=True)
	return result.returncode == 0


def check_os_upgrade(system_name: str) -> bool:
	if system_name == OSCodeNames.MACOS.value:
		return False  # skip

	is_rpm = is_rpm_system(system_name)
	filename = "/etc/yum.repos.d/xcloud-agent.repo" if is_rpm else "/etc/apt/sources.list.d/xcloud-agent.list"

	if not os.path.isfile(filename):
		print(f"{filename} not found!")
		return False  # no need upgrade

	with open(filename, 'r') as file:
		filedata = file.read()

	prev_repo = ""
	for system_attributes in SUPPORTED_SYSTEMS.values():
		repo_name = system_attributes.get('repo')
		if repo_name and repo_name.value in filedata:
			prev_repo = repo_name.value
			break

	repo_suffix = "".join([suffix for suffix in ['-dev', '-stg'] if suffix in filedata])
	prev_repo += repo_suffix
	orig_repo = default_linux_repo(system_name) + repo_suffix
	if orig_repo == prev_repo:
		return False

	if is_rpm:
		if not update_cache("yum") or \
			not run_command(["yum", "remove", "efs-agent*", "-y"], error_text="remove efs-agent packages") or \
			not add_keys_and_repository_configuration(is_rpm, orig_repo):
			return False
	else:
		if filedata and filedata.startswith('#'):  # OS comments repo path after upgrade
			filedata = filedata[1:]
		filedata = filedata.replace(prev_repo, orig_repo)
		with open(filename, "w", encoding="utf-8") as file:
			file.write(filedata)

		if not update_cache("apt-get"):
			return False

	package_tool = "yum" if is_rpm else "apt-get"

	run_command([package_tool, "reinstall", "elastio-snap-dkms", "-y"])
	run_command([package_tool, "reinstall", "xcloud-agent", "-y"])

	run_command(["systemctl", "daemon-reload"])

	return run_command(['systemctl', 'is-active', "xcloud-agent"]) and \
		run_command(['systemctl', 'is-active', "xcloud-manager"])


def request_install_mode() -> bool:
	"""
	:returns: True in case of Cloud mode, False in case of Local mode
	:rtype: bool
	"""
	while True:
		answer = input("Please, choose the installation mode: Cloud or Local (c/l): ").strip().lower()
		if answer in ("c", "cloud"):
			return True

		if answer in ("l", "local"):
			return False


def is_agent_installed() -> bool:
	app_path = "bin/xcloud-agent"
	base_path = get_macos_agent_path() if is_macos() else "/usr/"
	install_path = os.path.join(base_path, app_path)
	return os.path.exists(install_path)


def get_macos_agent_path() -> str:
	return "/Applications/xcloud.app/Contents/"


def update_agent(args: argparse.Namespace, system_name: str) -> bool:
	if system_name != OSCodeNames.MACOS.value:
		package_tool = "yum" if is_rpm_system(system_name) else "apt-get"
		return install_package_in_linux("xcloud-agent", package_tool)

	package_path = download_macos_package(args.pkg_url)
	if package_path:
		return install_package_in_macos(package_path)

	return False


def install_package_in_linux(package_name: str, tool: str, refresh: bool = False) -> bool:
	command = [tool, "-y", "install"]
	if tool == "apt-get":
		command.extend(["--no-install-recommends", "--no-install-suggests"])
	if refresh:
		command.append("--refresh")
	command.append(package_name)
	return run_command(command, error_text=f"install the package {package_name}")


def run_command(command: List, error_text: str = "") -> bool:
	result = subprocess.run(
		command, stdout=sys.stdout, stderr=subprocess.PIPE, universal_newlines=True, encoding='utf-8')
	if result.returncode == 0:
		return True

	if error_text:
		print(f"Failed to {error_text}:\n{result.stderr}")
	return False


def download_macos_package(url: str) -> Optional[str]:
	if run_command(["curl", "-O", url], error_text=f"download the package from {url}"):
		return url.split('/')[-1]

	return None


def install_package_in_macos(package_path: str) -> bool:
	return run_command(
		["installer", "-pkg", package_path, "-target", "/"],
		error_text=f"install the package {package_path}")


def should_keep_config_settings(system_name: str) -> bool:
	exist_config = exist_config_file(system_name)
	if not exist_config:
		return False

	keep_config = request_yes_or_no(
		"Found the config file from previous installation. Do you want to use it as the current configuration? (y/n): ")
	return keep_config


def exist_config_file(system_name: str) -> bool:
	config_file = get_config_path(ConfigFiles.CONFIG.value)
	if is_macos():
		config_file += ".macsave"
	elif is_rpm_system(system_name):
		config_file += ".rpmsave"

	return os.path.isfile(config_file)


def get_config_path(filename: str) -> str:
	path = "/etc/xcloud" if not is_macos() else os.path.join(get_macos_agent_path(), "etc")
	file_path = os.path.join(path, filename)
	return file_path


def install_agent(args: argparse.Namespace, system_name: str) -> bool:
	print_install_source(args)

	if system_name == OSCodeNames.MACOS.value:
		return install_agent_in_macos(args)

	return install_agent_in_linux(args, system_name)


def print_install_source(args: argparse.Namespace) -> None:
	remote = args.pkg_url if is_macos() else args.repo_name
	source = f"local package {args.local}" if args.local else remote
	print(f"Install agent from {source}")


def install_agent_in_macos(args: argparse.Namespace) -> bool:
	package_path = args.local or download_macos_package(args.pkg_url)
	if not package_path or not install_package_in_macos(package_path):
		return False

	return True


def get_macos_service_file() -> str:
	return os.path.join(get_macos_agent_path(), "etc/", get_service_name())


def get_service_name() -> str:
	service_name = "com.axcient.xcloud-agent.plist" if is_macos() else "xcloud-agent.service"
	return service_name


def install_agent_in_linux(args: argparse.Namespace, system_name: str) -> bool:
	is_rpm = is_rpm_system(system_name)
	package_tool = "yum" if is_rpm else "apt-get"

	add_keys_and_repository_configuration(is_rpm, args.repo_name)

	if is_rpm:
		if not clean_all_packages(package_tool) \
				or not update_cache(package_tool) \
				or not install_epel(system_name):
			return False
	else:
		update_cache(package_tool)
		if not install_ca_certificates(package_tool):
			return False

	if not install_dependencies(system_name, package_tool):
		return False

	if args.local:
		result = install_local_package_in_linux(args.local, system_name)
	else:
		result = install_package_in_linux("xcloud-agent", package_tool)
	if not result:
		return False

	if is_rpm:
		update_config_files_in_rpm()
		if not run_command(["ldconfig"], error_text=f"run ldconfig"):
			return False

	return run_command(["modprobe", "elastio-snap"], error_text="add elastio-snap to Linux kernel")


def add_keys_and_repository_configuration(is_rpm: bool, repo_name: str) -> bool:
	if is_rpm:
		temp_file_name = "temp_keys.rpm"
		url = f"http://yum.slc.efscloud.net/agent/efs-agent-{repo_name}.noarch.rpm"
		# RedHat rpms contain both key and repo file
		command = ["yum", "-y", "localinstall", temp_file_name]
	else:
		temp_file_name = "temp_keys.deb"
		url = "https://repo.replibit.net/agent/pool/main/e/efs-archive-keyring/efs-archive-keyring_20220801_all.deb"
		# Debian/Ubuntu debs contain only key file
		command = ["dpkg", "-i", temp_file_name]

	if not download_linux_package(temp_file_name, url):
		return False

	result_install = subprocess.run(
		command, stdout=sys.stdout, stderr=subprocess.PIPE, universal_newlines=True, encoding='utf-8')
	os.remove(temp_file_name)
	if result_install.returncode:
		print(f"Failed to install {temp_file_name}:\n{result_install.stderr}")
		return False

	if not is_rpm:
		add_repository_configuration(repo_name)

	return True


def download_linux_package(filename: str, url: str) -> bool:
	return run_command(["wget", "-O", filename, url], error_text=f"download the package from {url}")


def clean_all_packages(tool: str) -> bool:
	return run_command([tool, "clean", "all"], error_text="to clean out all packages and meta data from cache")


def add_repository_configuration(repo_name: str) -> None:
	sources_list_content = f"deb [arch=amd64] https://repo.replibit.net/agent {repo_name} main"
	sources_list_file = "/etc/apt/sources.list.d/xcloud-agent.list"
	with open(sources_list_file, "w", encoding="utf-8") as file:
		file.write(sources_list_content)


def update_cache(tool: str) -> bool:
	command = "makecache" if tool == "yum" else "update"
	return run_command([tool, command, "-y"], error_text="update the package database")


def install_epel(system_name: str) -> bool:
	epel_version = get_epel_version(system_name)
	if epel_version is None:
		return True

	if is_epel_installed():
		print(f"epel-release-{epel_version} is already installed")
		return True

	package_name = f"https://dl.fedoraproject.org/pub/epel/epel-release-latest-{epel_version}.noarch.rpm"
	command = ["yum", "-y", "install", package_name]
	return run_command(command, error_text=f"install the package {package_name}")


def get_epel_version(system_name: str) -> Optional[int]:
	key_name = 'epel_version'
	system_attributes = SUPPORTED_SYSTEMS.get(system_name, {})
	if system_attributes:
		return system_attributes.get(key_name)

	if is_rpm_system(system_name):
		return SUPPORTED_SYSTEMS['Red Hat Enterprise Linux 8'].get(key_name)

	return None


def is_epel_installed() -> bool:
	return run_command(["yum", "list", "installed", "epel-release"])


def install_ca_certificates(tool: str) -> bool:
	install_package_in_linux("ca-certificates", tool)
	return update_cache(tool)


def install_dependencies(system_name: str, tool: str) -> bool:
	refresh = exist_refresh(system_name)

	result_uname = subprocess.run(
		["uname", "-r"], stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True, encoding='utf-8')
	if result_uname.returncode:
		print(f"Failed to get kernel version:\n{result_uname.stderr}")
		return False

	kernel_version = result_uname.stdout.strip()
	uek_suffix = "uek-" if "uek" in kernel_version else ""
	kernel_package = f"linux-headers" if not is_rpm_system(system_name) else f"kernel-{uek_suffix}devel"
	kernel_package += f"-{kernel_version}"

	packages = ("dkms", kernel_package, "elastio-snap-utils")
	for package in packages:
		if not install_package_in_linux(package, tool, refresh):
			return False

	return True


def exist_refresh(system_name: str) -> bool:
	key_name = 'exist_refresh'
	system_attributes = SUPPORTED_SYSTEMS.get(system_name, {})
	if system_attributes:
		return system_attributes.get(key_name, False)

	if is_rpm_system(system_name):
		return SUPPORTED_SYSTEMS['Red Hat Enterprise Linux 8'].get(key_name, False)

	return False


def install_local_package_in_linux(package_name: str, system_name: str) -> bool:
	if is_rpm_system(system_name):
		return run_command(["yum", "localinstall", "-y", package_name])

	package_name = get_relative_path(package_name)
	os_system = SUPPORTED_SYSTEMS.get(system_name, {}).get('os')
	if os_system == OSCodeNames.DEBIAN:
		return install_package_in_debian(package_name)

	return install_package_in_linux(package_name, "apt-get")


def get_relative_path(path: str) -> str:
	if not path.startswith(('/', './')):
		path = './' + path

	return path


def install_package_in_debian(package_name: str) -> bool:
	if run_command(["dpkg", "-i", package_name]):
		return True

	print("Trying to fix Debian dependencies...")
	return run_command(["apt-get", "install", "--no-install-recommends", "--no-install-suggests", "-f", "-y"], "fix broken dependencies")


def update_config_files_in_rpm() -> None:
	for file in ConfigFiles:
		config_path = get_config_path(file.value)
		config_rpmsave_path = config_path + ".rpmsave"
		if os.path.isfile(config_rpmsave_path):
			run_command(["cp", config_rpmsave_path, config_path], error_text=f"copy the file {config_rpmsave_path}")


def prepare_config(args: argparse.Namespace) -> None:
	file_path = get_config_path(ConfigFiles.CONFIG.value)

	with open(file_path, "r", encoding="utf-8") as file:
		file_contents = file.read()

	if args.password:
		salt = generate_random_salt()
		salt_hex = salt.hex()
		# Use salt in hex format, because backend does the same.
		# See https://github.com/Axcient/Replibit/blob/7399e038f311b41c8526dad483eaecbf0aab7640/src/flask/replibit.py#L1427
		password_hashed = hash_data(bytes(args.password, encoding="utf-8"), bytes(salt_hex, encoding="utf-8"))
		file_contents = set_value(file_contents, "signature", password_hashed.hex())
		file_contents = set_value(file_contents, "nacl", salt_hex)

	if args.token_id is None:
		file_contents = set_value(file_contents, "server", args.appliance_address)
	else:
		file_contents = set_value(file_contents, "reg_id", args.token_id)

	file_contents = set_value(file_contents, "auth_url", args.auth_url)

	with open(file_path, "w", encoding="utf-8") as file:
		file.write(file_contents)


def generate_random_salt() -> bytes:
	sha512_len = 64
	return os.urandom(sha512_len)


def hash_data(data_bytes: bytes, salt: bytes) -> bytes:
	hasher = hashlib.sha512()
	hasher.update(data_bytes + salt)
	return hasher.digest()


def set_value(file_contents: str, key: str, value: str) -> str:
	new_line = f"{key} = '{value}'"
	lines = file_contents.split('\n')
	for i, line in enumerate(lines):
		if line.startswith(key + " = "):
			lines[i] = new_line
			break
	else:
		lines.append(new_line)

	return '\n'.join(lines)


def restart_service() -> bool:
	service_name = get_service_name()
	success = restart_service_in_macos(get_macos_service_file()) if is_macos() else restart_service_in_linux(
		service_name)
	if not success:
		print(f"Cannot restart {service_name} service")
	return success


def restart_service_in_macos(service_name: str) -> bool:
	return run_command(["launchctl", "unload", service_name]) and run_command(["launchctl", "load", service_name])


def restart_service_in_linux(service_name: str) -> bool:
	return run_command(["systemctl", "restart", service_name]) and run_command(["systemctl", "enable", service_name])


def main() -> int:
	system_name = check_system_requirements()
	if not system_name:
		return 1

	args = get_script_args(system_name)

	if args.update is not None:
		return check_os_upgrade(system_name)

	if is_agent_installed():
		print("Already installed. Try to update.")
		return update_agent(args, system_name)

	keep_config_settings = should_keep_config_settings(system_name)
	success = install_agent(args, system_name)
	if not success:
		return 1

	if not keep_config_settings:
		prepare_config(args)

	success = restart_service()
	return success


if __name__ == "__main__":
	sys.exit(main())
