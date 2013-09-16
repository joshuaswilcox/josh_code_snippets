class Bottle < ActiveRecord::Base
  include ActionView::Helpers::DateHelper
	default_scope :order => 'created_at DESC'
	belongs_to :user
	has_many :bottle_comments
  has_many :bottle_images
	has_many :wants, :dependent => :destroy
	alias_attribute :qqfile, :image
	validates_presence_of :brand, :region, :age, :price, :unless => Proc.new { |ex| ex.status == 'initial' }
  attr_accessible :age, :brand, :image, :notes, :price, :region, :user_id, :kind, :date_aquired, :status, :deleted
  validates_inclusion_of :kind, :in => %w( Bourbon Whiskey Rye Scotch ), :on => :create, :message => "%s is not included in the list", :unless => Proc.new { |ex| ex.status == 'initial' }
  #mount_uploader :image, ImageUploader
  
	
	#scope :previous, lambda { |p| {:conditions => ["id < ?", p.id], :limit => 1, :order => "id"} }
	#scope :next, lambda { |p| {:conditions => ["id > ?", p.id], :limit => 1, :order => "id"} }
	
  scope :filtered, lambda { |filter| { :conditions => ['kind = ?', filter] }}
  
  default_scope where(:status => 'normal', :deleted => false)
  
	def want_count
		Want.where(:bottle_id => id).count
	end
	
	def want_user
		self.wants.map(&:user)
	end
  
  def comments_users
    self.bottle_comments.map(&:user)
  end	
	
	def next
		Bottle.where("id > ?", id).first
	end

	def prev
		Bottle.where("id < ?", id).last
	end
	
	def self.search(search)
		if search
			find(:all, :conditions => ['brand LIKE ?', "%#{search}%"])
		else
			find(:all)
		end
	end
  
  def as_json(options={})
    json = super()
    json['bottle_types'] = ["Bourbon", "Rye", "Scotch", "Whiskey"]
    #return json unless self.status != 'initial'
    json['time_f'] = time_ago_in_words(self.created_at)
    json['time_h'] = self.created_at.strftime('%m/%d/%Y')
    json['bottle_image'] = self.bottle_images.last
    json['user'] = self.user
    json['bottle_comments'] = self.bottle_comments
    json['want_count'] = self.want_count
    json['want_user'] = self.want_user
    json
  end
  
end