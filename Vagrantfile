
Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/bionic64"
  config.vm.synced_folder "./configs", "/configs"
  config.vm.provider "virtualbox" do |vb|
    vb.memory = 512
    vb.cpus = 1
  end
  
  # # Máquina MySqlDb provisionada via Shell
  # config.vm.define "mysqldb" do |mysql|
  #   mysql.vm.network "public_network", ip: "192.168.1.24"

  #   $script = <<-SCRIPT
  #     echo "**************** Atualizando e instalando MySQL ****************"
  #     apt-get update && \
  #     apt-get install -y mysql-server-5.7 && \
  #     mysql -e "create user 'phpuser'@'%' identified by 'pass';"
  #     echo "**************** Public Key ****************"
  #     cat /vagrant/id_bionic.pub >> .ssh/authorized_keys
  #     echo "**************** Configurando MySQL ****************"
  #     cat /configs/mysqld.cnf > /etc/mysql/mysql.conf.d/mysqld.cnf
  #     echo "**************** Restart MySQL ****************"
  #     systemctl restart mysql
  #     echo "**************** Instalando NGINX ****************"
  #     apt-get install -y nginx
  #     echo "**************** Maquina criada com sucesso ****************"
  #   SCRIPT

  #   mysql.vm.provision "shell", inline: $script
  # end

  # Máquina Php Web
  config.vm.define "phpweb" do |phpweb|
    phpweb.vm.network "forwarded_port", guest: 8888, host: 8888
    phpweb.vm.network "public_network", ip: "192.168.1.25"

    phpweb.vm.provider "virtualbox" do |vb|
      vb.name = "ubuntu_bionic_php"
    end

    $script = <<-SCRIPT
      echo "**************** Atualizando e instalando Puppet ****************"
      apt-get update && \
      apt-get install -y puppet
    SCRIPT

    phpweb.vm.provision "shell", inline: $script

    # Chamando o arquivo de conf Puppet
    phpweb.vm.provision "puppet" do |puppet|
      puppet.manifests_path = "./configs/manifests"
      puppet.manifest_file = "phpweb.pp"
    end
  end

  # MySQL SERVER
  config.vm.define "mysqlserver" do |mysqlserver|
    mysqlserver.vm.network "public_network", ip: "192.168.1.22"

    mysqlserver.vm.provider "virtualbox" do |vb|
      vb.name = "ubuntu_bionic_mysqlserver"
    end

    $script = <<-SCRIPT
      echo "**************** Atualizando server ****************"
      apt-get update
      echo "**************** Public Key ****************"
      cat /vagrant/id_bionic.pub >> .ssh/authorized_keys
    SCRIPT

    mysqlserver.vm.provision "shell", inline: $script
  end

  # Ansible
  config.vm.define "ansible" do |ansible|
    ansible.vm.network "public_network", ip: "192.168.1.26"

    ansible.vm.provider "virtualbox" do |vb|
      vb.name = "ubuntu_bionic_ansible"
    end

    $script = <<-SCRIPT
      cp /vagrant/id_bionic /home/vagrant && \
      chmod 600 /home/vagrant/id_bionic && \
      chown vagrant:vagrant /home/vagrant/id_bionic
      echo "**************** Atualizando e instalando Ansible ****************"
      apt-get update && \
      apt-get install -y software-properties-common && \
      apt-add-repository --yes --update ppa:ansible/ansible && \
      apt-get install -y ansible      
    SCRIPT

    ansible.vm.provision "shell", inline: $script

    # Chamando playbook
    ansible.vm.provision "shell",
      inline: "ansible-playbook -i /vagrant/configs/ansible/hosts \
                /vagrant/configs/ansible/playbook.yml"

  end
end
