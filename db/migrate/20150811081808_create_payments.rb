class CreatePayments < ActiveRecord::Migration
  def change
    create_table :payments do |t|
      t.decimal :total
      t.string :description
      t.string :curreny
      t.json :urls
      t.integer :client_id
      t.string :ref_no
      t.string :title
      t.json :items
      t.string :email
      t.json :info

      t.timestamps null: false
    end
  end
end
