import sqlite3
from sqlite3 import Error
import json
from json import JSONEncoder

class Creator:
    def __init__(self, role, descr):
        self.Role = role
        self.Description = descr

class Artwork(json.JSONEncoder):
    def __init__(self, accNo, title, tombstone, creator, dept):
        self.AccessionNumber = accNo
        self.Title = title
        self.Tombstone = tombstone
        self.Creator = [creator]
        self.Department = dept

    def add_creator(self, creator):
        self.Creator.append(creator)
    
class ArtworkEncoder(JSONEncoder):
    def default(self, o):
        return o.__dict__

def dict_factory(cursor, row):
    d = {}
    for idx, col in enumerate(cursor.description):
        d[col[0]] = row[idx]
    return d

def parse_query_results(results):
    i=0
    artworkList = []
    for row in results:
        if i > 0:
            if artworkList[i-1].AccessionNumber == row['accession_number']:
                artworkList[i-1].add_creator(Creator(row['role'], row['description']))
            else:
                artworkList.append(Artwork(row['accession_number'], row['title'], row['tombstone'], Creator(row['role'], row['description']), row['department']))
                i = i + 1
        else:
            artworkList.append(Artwork(row['accession_number'], row['title'], row['tombstone'], Creator(row['role'], row['description']), row['department']))
            i = i + 1
    return artworkList

def main():
    #set up db conn
    database = r"cma-artworks.db"
    connection = sqlite3.connect(database)
    connection.row_factory = dict_factory
    cursor = connection.cursor()
    
    #execute sql
    query = "select distinct a.accession_number, a.title, a.tombstone, c.id, c.role, c.description, d.name as department from artwork a inner join artwork__creator ac on ac.artwork_id=a.id inner join creator c on c.id=ac.creator_id and c.role<>'NULL' inner join artwork__department ad on ad.artwork_id=a.id inner join department d on d.id=ad.department_id order by a.accession_number, d.id"
    cursor.execute(query)
    results = cursor.fetchall()

    #process results into list of objects
    artworkList = parse_query_results(results)
    
    #output list to json
    print(ArtworkEncoder().encode(artworkList))
    
    connection.close()
    
if __name__ == '__main__':
    main()
    